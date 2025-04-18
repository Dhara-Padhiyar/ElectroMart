<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$orders = [];

// Handle POST request for cancel or return actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $order_id = (int)($_POST['order_id'] ?? 0);
    $reason = trim($_POST['reason'] ?? '');

    $stmt = $conn->prepare("SELECT status FROM orders WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $order = $result->fetch_assoc();

        if ($action === 'cancel' && $order['status'] === 'pending') {
            $update_stmt = $conn->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
            $update_stmt->bind_param("i", $order_id);
            if ($update_stmt->execute()) {
                $log_stmt = $conn->prepare("INSERT INTO order_cancellations (order_id, user_id, reason, cancelled_at) VALUES (?, ?, ?, NOW())");
                $log_stmt->bind_param("iis", $order_id, $user_id, $reason);
                $log_stmt->execute();
                echo json_encode(['success' => true]);
                exit;
            }
        } elseif ($action === 'return' && $order['status'] === 'delivered') {
            $return_stmt = $conn->prepare("INSERT INTO order_returns (order_id, user_id, reason, requested_at) VALUES (?, ?, ?, NOW())");
            $return_stmt->bind_param("iis", $order_id, $user_id, $reason);
            if ($return_stmt->execute()) {
                echo json_encode(['success' => true]);
                exit;
            }
        }
    }

    echo json_encode(['success' => false]);
    exit;
}

// Fetch orders
$stmt = $conn->prepare("SELECT id, total_price, status, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($order = $result->fetch_assoc()) {
    $order_id = $order['id'];
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
    <title>Returns & Cancellations</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/stylesheet.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="dashboard-container">
        <?php include 'dashboard-sidebar.php'; ?>

        <main class="profile-main-content">
            <h1><i class="fas fa-undo"></i> Returns & Cancellations</h1>

            <div class="orders-container">
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $order): ?>
                        <div class="order-item">
                            <div class="order-header">
                                <div>
                                    <span class="order-id">Order #<?php echo $order['id']; ?></span><br>
                                    <span class="order-date">Placed on <?php echo date('M d, Y', strtotime($order['created_at'])); ?></span>
                                </div>
                                <span class="order-status status-<?php echo strtolower($order['status']); ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </div>

                            <div class="order-products">
                                <?php foreach ($order['products'] as $product): ?>
                                    <img src="../images/<?php echo $product['image']; ?>" class="order-product-img" alt="<?php echo htmlspecialchars($product['name']); ?>" title="<?php echo htmlspecialchars($product['name']); ?>">
                                <?php endforeach; ?>
                            </div>

                            <div class="order-footer">
                                <div class="order-total">Total: $<?php echo number_format($order['total_price'], 2); ?></div>
                                <div class="order-actions">
                                    <a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-file-invoice"></i> Details
                                    </a>
                                    <?php if ($order['status'] === 'pending'): ?>
                                        <button onclick="openModal('cancel', <?php echo $order['id']; ?>)" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-times"></i> Cancel
                                        </button>
                                    <?php endif; ?>
                                    <?php if ($order['status'] === 'delivered'): ?>
                                        <button onclick="openModal('return', <?php echo $order['id']; ?>)" class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-undo"></i> Return
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-orders">
                        <i class="fas fa-box-open" style="font-size: 50px; margin-bottom: 15px;"></i>
                        <h3>No Orders Found</h3>
                        <p>You haven't placed any orders eligible for cancellation or return.</p>
                        <a href="product.php" class="btn btn-primary mt-3">
                            <i class="fas fa-shopping-bag"></i> Start Shopping
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <?php include 'footer.php'; ?>

    <!-- Modal -->
    <div id="actionModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3 id="modalTitle"></h3>
            <form id="actionForm">
                <input type="hidden" name="action" id="actionType">
                <input type="hidden" name="order_id" id="orderId">
                <label for="reason">Reason:</label><br>
                <textarea name="reason" id="reason" rows="4" style="width: 100%;" required></textarea><br><br>
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>

    <script>
        function openModal(action, orderId) {
            document.getElementById('actionModal').style.display = 'block';
            document.getElementById('actionType').value = action;
            document.getElementById('orderId').value = orderId;
            document.getElementById('modalTitle').textContent = (action === 'cancel' ? 'Cancel Order' : 'Return Order');
        }

        function closeModal() {
            document.getElementById('actionModal').style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target === document.getElementById('actionModal')) {
                closeModal();
            }
        }

        document.getElementById('actionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Action completed successfully.');
                    location.reload();
                } else {
                    alert('Action failed.');
                }
            });
        });
    </script>
</body>
</html>
