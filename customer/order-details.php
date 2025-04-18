<?php
include '../config/db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Order ID not provided.");
}

$order_id = intval($_GET['id']);

// Fetch order info
$stmt = $conn->prepare("
    SELECT o.*, u.name AS user_name, u.email, u.phone, u.address
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE o.id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    die("Order not found.");
}

// Fetch order items
$stmt = $conn->prepare("
    SELECT oi.*, p.name, p.image
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items_result = $stmt->get_result();
$order_items = $items_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order #<?= $order['id'] ?> Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<?php include 'header.php'; ?>
<div class="container order-main-content">
    <h1><i class="fas fa-box"></i> Order Details - #<?= $order['id'] ?> <span style="font-size: 14px;">(<?= ucfirst($order['status']) ?>)</span></h1>

    <div class="order-info">
        <div class="box">
            <h3>Customer</h3>
            <p><strong>Name:</strong> <?= htmlspecialchars($order['user_name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($order['phone']) ?></p>
        </div>

        <div class="box">
            <h3>Order Info</h3>
            <p><strong>Date:</strong> <?= date('M d, Y H:i', strtotime($order['created_at'])) ?></p>
            <p><strong>Status:</strong> <?= ucfirst($order['status']) ?></p>
            <p><strong>Total:</strong> $<?= number_format($order['total_price'], 2) ?></p>
        </div>

        <div class="box">
            <h3>Shipping Address</h3>
            <p><?= nl2br(htmlspecialchars($order['address'])) ?></p>
        </div>
    </div>

    <h3>Items</h3>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $subtotal = 0;
            foreach ($order_items as $item):
                $line_total = $item['price'] * $item['quantity'];
                $subtotal += $line_total;
            ?>
            <tr>
                <td>
                    <div class="flex">
                        <img src="../images/<?= htmlspecialchars($item['image']) ?>" class="product-img" alt="">
                        <?= htmlspecialchars($item['name']) ?>
                    </div>
                </td>
                <td>$<?= number_format($item['price'], 2) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>$<?= number_format($line_total, 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <table class="totals">
        <tr><td>Subtotal:</td><td>$<?= number_format($subtotal, 2) ?></td></tr>
        <tr><td>Total:</td><td>$<?= number_format($order['total_price'], 2) ?></td></tr>
    </table>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
