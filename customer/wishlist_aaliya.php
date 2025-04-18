<?php
include '../config/db.php';
session_start();

$user_id = $_SESSION['user_id'] ?? 0;

// ✅ Handle Add to Cart from Wishlist (no JS)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'] ?? 0;

    // Get quantity (default to 1)
    $quantity = 1;

    // Check if already in cart
    $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
    $stmt->close();

    if ($item) {
        // Update quantity
        $new_quantity = $item['quantity'] + $quantity;
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt->bind_param("ii", $new_quantity, $item['id']);
        $stmt->execute();
        $stmt->close();
    } else {
        // Insert new cart item
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);
        $stmt->execute();
        $stmt->close();
    }

    // ✅ Remove from wishlist
    $stmt = $conn->prepare("DELETE FROM wiselist WHERE customerid = ? AND productid = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $stmt->close();

    // Reload page to show updated wishlist
    header("Location: wishlist.php?status=added");
    exit;
}

// ✅ Fetch Wishlist
$wishlist_items = [];
if ($user_id > 0) {
    $stmt = $conn->prepare("SELECT p.* FROM wiselist w JOIN products p ON w.productid = p.id WHERE w.customerid = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $wishlist_items[] = $row;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Wishlist</title>
    <link rel="stylesheet" href="public/css/stylesheet.css">
    <style>
        .wishlist-container { display: flex; flex-wrap: wrap; gap: 20px; }
        .wishlist-item { border: 1px solid #ccc; padding: 20px; width: 300px; background: #fff; border-radius: 8px; }
        .wishlist-item img { width: 100%; height: 200px; object-fit: cover; }
        .wishlist-item-title { font-size: 18px; margin-top: 10px; }
        .wishlist-item-price { color: green; margin-top: 5px; font-weight: bold; }
        .wishlist-item form { margin-top: 10px; }
        .wishlist-item button { padding: 8px 12px; margin-right: 10px; }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="profile-container">
    <?php include 'dashboard-sidebar.php'; ?>

    <main class="profile-main-content">
        <h1><i class="fas fa-heart"></i> My Wishlist</h1>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'added'): ?>
            <div style="padding:10px;background:#d4edda;color:#155724;border:1px solid #c3e6cb;margin-bottom:15px;">
                Item added to cart and removed from wishlist.
            </div>
        <?php endif; ?>

        <div class="wishlist-container">
            <?php if (count($wishlist_items) > 0): ?>
                <?php foreach ($wishlist_items as $item): ?>
                    <div class="wishlist-item">
                        <img src="../images/<?php echo $item['image']; ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                        <h3 class="wishlist-item-title"><?= htmlspecialchars($item['name']) ?></h3>
                        <div class="wishlist-item-price">$<?= number_format($item['price'], 2) ?></div>

                        <form method="POST" action="wishlist.php">
                            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                            <button type="submit" name="add_to_cart">Add to Cart</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align:center; padding: 40px;">
                    <h2><i class="fas fa-heart-broken"></i> Your wishlist is empty.</h2>
                    <a href="product.php" class="btn">Browse Products</a>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>
<?php include 'footer.php'; ?>
</body>
</html>