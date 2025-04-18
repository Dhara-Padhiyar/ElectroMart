<?php
include '../config/db.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$customer_id = $_SESSION['user_id'];
$orders = [];

// Fetch customer orders
$sql = "SELECT o.id, o.created_at AS date, o.total_price AS total, o.status,
            (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) AS item_count
        FROM orders o
        WHERE o.user_id = ?
        ORDER BY o.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="public/css/stylesheet.css">
    <style>
        .leave-review {
            color: #ffc107;
            margin-left: 10px;
            text-decoration: none;
            font-weight: 500;
        }
        .leave-review:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="profile-container" style="display: flex;">
        <?php include 'dashboard-sidebar.php'; ?>

        <main class="profile-main-content" style="flex-grow: 1; padding: 35px 30px 30px 30px">
            <h1><i class="fas fa-history"></i> Order History</h1>

            <div class="orders-container">
                <?php if (count($orders) > 0) : ?>
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?php echo $order['id']; ?></td>
                                    <td><?php echo date('M d, Y', strtotime($order['date'])); ?></td>
                                    <td><?php echo $order['item_count']; ?></td>
                                    <td>$<?php echo number_format($order['total'], 2); ?></td>
                                    <td>
                                        <span class="order-status status-<?php echo strtolower($order['status']); ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </td>
                                    <td class="order-actions">
                                        <a href="order-details.php?id=<?php echo $order['id']; ?>">
                                            <i class="fas fa-eye"></i> View
                                        </a>

                                        <?php if ($order['status'] === 'pending' || $order['status'] === 'processing'): ?>
                                            <a href="#" class="cancel-order" data-id="<?php echo $order['id']; ?>">
                                                <i class="fas fa-times"></i> Cancel
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($order['status'] === 'delivered'): ?>
                                            <a href="reviews.php?order_id=<?php echo $order['id']; ?>" class="leave-review">
                                                <i class="fas fa-star"></i> Review
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="pagination">
                        <a href="#">&laquo;</a>
                        <a href="#" class="active">1</a>
                        <a href="#">2</a>
                        <a href="#">3</a>
                        <a href="#">&raquo;</a>
                    </div>
                <?php else : ?>
                    <div class="empty-orders">
                        <i class="fas fa-box-open" style="font-size: 50px; margin-bottom: 20px;"></i>
                        <h3>No Orders Found</h3>
                        <p>You haven't placed any orders yet.</p>
                        <a href="shop.php" class="btn" style="margin-top: 15px;">Start Shopping</a>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        // Cancel order confirmation
        document.querySelectorAll('.cancel-order').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const orderId = this.getAttribute('data-id');

                if (confirm('Are you sure you want to cancel this order?')) {
                    window.location.href = `cancel-order.php?id=${orderId}`;
                }
            });
        });
    </script>
</body>
</html>
