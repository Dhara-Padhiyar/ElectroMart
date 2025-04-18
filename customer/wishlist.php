<?php
include '../config/db.php';
session_start();

$user_id = $_SESSION['user_id'] ?? 0;

// ✅ Handle AJAX Remove from Wishlist
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest'
) {
    header('Content-Type: application/json');

    parse_str(file_get_contents("php://input"), $_DELETE);

    if (isset($_DELETE['remove_product']) && isset($_DELETE['product_id'])) {
        $product_id = (int) $_DELETE['product_id'];

        if (!$user_id) {
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM wiselist WHERE customerid = ? AND productid = ?");
        $stmt->bind_param("ii", $user_id, $product_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Product removed from wishlist']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error removing product']);
        }

        $stmt->close();
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// ✅ Handle Add to Cart from Wishlist (non-JS form)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'], $_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = 1;

    $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
    $stmt->close();

    if ($item) {
        $new_quantity = $item['quantity'] + $quantity;
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt->bind_param("ii", $new_quantity, $item['id']);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);
        $stmt->execute();
        $stmt->close();
    }

    // Remove from wishlist
    $stmt = $conn->prepare("DELETE FROM wiselist WHERE customerid = ? AND productid = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $stmt->close();

    header("Location: wishlist.php?status=added");
    exit;
}

// ✅ Fetch Wishlist Items
$wishlist_items = [];
$stmt = $conn->prepare("SELECT p.*, w.* FROM wiselist w JOIN products p ON w.productid = p.id WHERE w.customerid = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $wishlist_items[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Wishlist</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/stylesheet.css"> <!-- path unchanged as per request -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="profile-container">
    <?php include 'dashboard-sidebar.php'; ?>

    <main class="profile-main-content">
        <h1><i class="fas fa-heart"></i> My Wishlist</h1>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'added'): ?>
            <div class="alert alert-success">
                Item added to cart and removed from wishlist.
            </div>
        <?php endif; ?>

        <div class="wishlist-container">
            <?php if (count($wishlist_items) > 0): ?>
                <?php foreach ($wishlist_items as $item): ?>
                    <div class="wishlist-item">
                        <img src="../images/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="wishlist-item-img">

                        <div class="wishlist-item-content">
                            <h3 class="wishlist-item-title"><?php echo $item['name']; ?></h3>
                            <div class="wishlist-item-price">₹<?php echo number_format($item['price'], 2); ?></div>

                            <div class="wishlist-item-actions d-flex justify-content-between mt-2">
                                <!-- Add to Cart Form -->
                                <form method="POST" action="wishlist.php">
                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" name="add_to_cart" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                </form>

                                <!-- Remove via JS -->
                                <button class="btn btn-sm btn-outline-danger remove-item" data-id="<?php echo $item['id']; ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="wishlist-empty text-center py-5">
                    <i class="fas fa-heart-broken fa-2x"></i>
                    <h3>Your wishlist is empty</h3>
                    <p>Save your favorite items here to keep track of them</p>
                    <a href="product.php" class="btn btn-primary">Browse Products</a>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php include 'footer.php'; ?>

<script>
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-id');
            if (confirm('Remove this item from your wishlist?')) {
                fetch('wishlist.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `product_id=${productId}&remove_product=true`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.closest('.wishlist-item').remove();
                        if (document.querySelectorAll('.wishlist-item').length === 0) {
                            location.reload();
                        }
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(err => {
                    console.error('Fetch error:', err);
                    alert('Something went wrong.');
                });
            }
        });
    });
</script>
</body>
</html>
