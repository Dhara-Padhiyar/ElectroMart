<?php
session_start();
include '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$orders = [];

// Fetch orders for the logged-in user
$stmt = $conn->prepare("SELECT id, total_price, status, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($order = $result->fetch_assoc()) {
    $order_id = $order['id'];

    // Fetch products for each order
    $product_stmt = $conn->prepare("
        SELECT p.name, p.image
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = ?
    ");
    $product_stmt->bind_param("i", $order_id);
    $product_stmt->execute();
    $product_result = $product_stmt->get_result();

    $products = [];
    while ($product = $product_result->fetch_assoc()) {
        $products[] = $product;
    }

    $order['products'] = $products;
    $orders[] = $order;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Track Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/stylesheet.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="dashboard-container">
        <?php include 'dashboard-sidebar.php'; ?>

        <main class="profile-main-content">
            <h1><i class="fas fa-truck"></i> Track Orders</h1>

            <div class="orders-container">
                <?php if (!empty($orders)): ?>
                    <div class="order-tabs">
                        <div class="order-tab active">All Orders</div>
                        <div class="order-tab">Processing</div>
                        <div class="order-tab">Shipped</div>
                        <div class="order-tab">Delivered</div>
                    </div>

                    <?php foreach ($orders as $order): ?>
                    <div class="order-item">
                        <div class="order-header">
                            <div>
                                <span class="order-id">Order #<?php echo $order['id']; ?></span>
                                <span class="order-date">Placed on <?php echo date('M d, Y', strtotime($order['created_at'])); ?></span>
                            </div>
                            <span class="order-status status-<?php echo strtolower($order['status']); ?>">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </div>

                        <?php if (in_array($order['status'], ['shipped', 'delivered'])): ?>
                        <div class="tracking-steps">
                            <div class="tracking-step">
                                <div class="step-icon <?php echo in_array($order['status'], ['processing', 'shipped', 'delivered']) ? 'active' : ''; ?>">
                                    <i class="fas fa-box-open"></i>
                                </div>
                                <div class="step-label <?php echo in_array($order['status'], ['processing', 'shipped', 'delivered']) ? 'active' : ''; ?>">Processing</div>
                            </div>
                            <div class="tracking-step">
                                <div class="step-icon <?php echo in_array($order['status'], ['shipped', 'delivered']) ? 'active' : ''; ?>">
                                    <i class="fas fa-shipping-fast"></i>
                                </div>
                                <div class="step-label <?php echo in_array($order['status'], ['shipped', 'delivered']) ? 'active' : ''; ?>">Shipped</div>
                            </div>
                            <div class="tracking-step">
                                <div class="step-icon <?php echo $order['status'] === 'delivered' ? 'active' : ''; ?>">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="step-label <?php echo $order['status'] === 'delivered' ? 'active' : ''; ?>">Delivered</div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="order-products">
                            <?php foreach ($order['products'] as $product): ?>
                            <img src="../images/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="order-product-img" title="<?php echo htmlspecialchars($product['name']); ?>">
                            <?php endforeach; ?>
                        </div>

                        <div class="order-footer">
                            <div class="order-total">Total: $<?php echo number_format($order['total_price'], 2); ?></div>
                            <div class="order-actions">
                                <a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-file-invoice"></i> Details
                                </a>
                                <?php if ($order['status'] === 'pending'): ?>
                                <a href="#" class="btn btn-outline-danger btn-sm cancel-order" data-id="<?php echo $order['id']; ?>">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                                <?php endif; ?>
                                <?php if ($order['status'] === 'delivered'): ?>
                                <a href="#" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-undo"></i> Return
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-orders">
                        <i class="fas fa-box-open" style="font-size: 50px; margin-bottom: 15px;"></i>
                        <h3>No Orders Found</h3>
                        <p>You haven't placed any orders yet.</p>
                        <a href="product.php" class="btn btn-primary mt-3">
                            <i class="fas fa-shopping-bag"></i> Start Shopping
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        // Order tabs functionality
        document.querySelectorAll('.order-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelector('.order-tab.active').classList.remove('active');
                this.classList.add('active');
                // Add filtering logic here if needed
            });
        });

        // Cancel order confirmation
        document.querySelectorAll('.cancel-order').forEach(button => {
            button.addEventListener('click', function(e) {

::contentReference[oaicite:2]{index=2}
 
e.preventDefault();
            const orderId = this.getAttribute('data-id');
            if (confirm('Are you sure you want to cancel this order?')) {
                // Send AJAX request to cancel the order
                fetch(`cancel-order.php?id=${orderId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Order canceled successfully.');
                        location.reload();
                    } else {
                        alert('Failed to cancel the order.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while canceling the order.');
                });
            }
        });
    </script>
</body>
</html>
