<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "vendor") {
    header("Location: ../auth/login.php");
    exit;
}

// Database connection
require_once '../config/db.php';
//$db = new Database();
//$pdo = $db->getConnection();

// Fetch vendor's orders
$sql = "
    SELECT o.id, o.created_at AS order_date, o.total_price AS total_amount, o.status, 
           u.name AS customer_name, u.email,
           COUNT(oi.id) AS item_count
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    JOIN users u ON o.user_id = u.id
    WHERE p.vendor_id = ?
    GROUP BY o.id
    ORDER BY o.created_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders | Vendor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/stylesheet.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container">
        <header class="dashboard-header">
            <h2>Order Management</h2>
            <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
        </header>

        

        <main class="dashboard-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Your Orders</h3>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown">
                        Filter by Status
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?status=all">All Orders</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="?status=pending">Pending</a></li>
                        <li><a class="dropdown-item" href="?status=processing">Processing</a></li>
                        <li><a class="dropdown-item" href="?status=shipped">Shipped</a></li>
                        <li><a class="dropdown-item" href="?status=delivered">Delivered</a></li>
                        <li><a class="dropdown-item" href="?status=cancelled">Cancelled</a></li>
                    </ul>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
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
                            <td>#<?= $order['id'] ?></td>
                            <td>
                                <?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?>
                                <br><small class="text-muted"><?= htmlspecialchars($order['email']) ?></small>
                            </td>
                            <td><?= date('M j, Y', strtotime($order['order_date'])) ?></td>
                            <td><?= $order['item_count'] ?></td>
                            <td>$<?= number_format($order['total_amount'], 2) ?></td>
                            <td>
                                <span class="badge 
                                    <?= 
                                        $order['status'] == 'pending' ? 'bg-warning' : 
                                        ($order['status'] == 'processing' ? 'bg-info' : 
                                        ($order['status'] == 'shipped' ? 'bg-primary' : 
                                        ($order['status'] == 'delivered' ? 'bg-success' : 'bg-secondary')))
                                    ?>">
                                    <?= ucfirst($order['status']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="order_details.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <?php if ($order['status'] == 'pending' || $order['status'] == 'processing'): ?>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" 
                                                    id="statusDropdown<?= $order['id'] ?>" data-bs-toggle="dropdown">
                                                <i class="bi bi-gear"></i> Update
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="update_order_status.php?order_id=<?= $order['id'] ?>&status=processing">Mark as Processing</a></li>
                                                <li><a class="dropdown-item" href="update_order_status.php?order_id=<?= $order['id'] ?>&status=shipped">Mark as Shipped</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="update_order_status.php?order_id=<?= $order['id'] ?>&status=cancelled">Cancel Order</a></li>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <nav aria-label="Order pagination">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>