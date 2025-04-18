<?php
include '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_to'] = 'cart.php';
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle quantity updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $cart_id => $quantity) {
        $cart_id = (int)$cart_id;
        $quantity = (int)$quantity;
        if ($quantity > 0) {
            $conn->query("UPDATE cart SET quantity = $quantity WHERE id = $cart_id AND user_id = $user_id");
        } else {
            $conn->query("DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id");
        }
    }
    
    // Update cart count in session
    $cart_count = $conn->query("SELECT SUM(quantity) as total FROM cart WHERE user_id = $user_id")->fetch_assoc()['total'];
    $_SESSION['cart_count'] = $cart_count ?: 0;
    
    header("Location: cart.php");
    exit();
}

// Handle item removal
if (isset($_GET['remove'])) {
    $cart_id = (int)$_GET['remove'];
    $conn->query("DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id");
    
    // Update cart count in session
    $cart_count = $conn->query("SELECT SUM(quantity) as total FROM cart WHERE user_id = $user_id")->fetch_assoc()['total'];
    $_SESSION['cart_count'] = $cart_count ?: 0;
    
    header("Location: cart.php");
    exit();
}

// Get cart items with product details
$cart_items = $conn->query("
    SELECT c.id as cart_id, c.quantity, p.id as product_id, p.name, p.price, p.image 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = $user_id
");

$subtotal = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - ElectroMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .profile-main-content.container.mt-5 {
            margin: 60px auto !important;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
    
    <div class="container mt-5 profile-main-content">
        <h2>Your Shopping Cart</h2>
        
        <?php if ($cart_items->num_rows == 0): ?>
            <div class="alert alert-info mt-4">
                Your cart is empty. <a href="product.php" class="alert-link">Continue shopping</a>.
            </div>
        <?php else: ?>
            <form method="post" action="cart.php">
                <div class="table-responsive mt-4">
                    <table class="table cart-table">
                        <thead class="cart-head">
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($item = $cart_items->fetch_assoc()): 
                                $item_total = $item['price'] * $item['quantity'];
                                $subtotal += $item_total;
                            ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($item['image']) && file_exists("../images/" . $item['image'])): ?>
                                                <img src="../images/<?php echo htmlspecialchars($item['image']); ?>" class="img-thumbnail me-3" style="width: 80px; height: 80px; object-fit: contain;">
                                            <?php else: ?>
                                                <div class="bg-secondary text-white text-center me-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="bi bi-image"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <h5 class="mb-0"><?php echo htmlspecialchars($item['name']); ?></h5>
                                                <small class="text-muted">SKU: <?php echo $item['product_id']; ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                                    <td>
                                        <input type="number" name="quantities[<?php echo $item['cart_id']; ?>]" 
                                               value="<?php echo $item['quantity']; ?>" min="1" class="form-control" style="width: 80px;">
                                    </td>
                                    <td>$<?php echo number_format($item_total, 2); ?></td>
                                    <td>
                                        <a href="cart.php?remove=<?php echo $item['cart_id']; ?>" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <a href="product.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Continue Shopping
                        </a>
                    </div>
                    <div class="col-md-6 text-end">
                        <button type="submit" name="update_cart" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-repeat"></i> Update Cart
                        </button>
                        <a href="checkout.php" class="btn btn-primary ms-2">
                            <i class="bi bi-credit-card"></i> Proceed to Checkout
                        </a>
                    </div>
                </div>
                
                <div class="row mt-4 justify-content-end">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Order Summary</h5>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>$<?php echo number_format($subtotal, 2); ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Shipping:</span>
                                    <span>Calculated at checkout</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total:</span>
                                    <span>$<?php echo number_format($subtotal, 2); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php include 'footer.php'; ?>
