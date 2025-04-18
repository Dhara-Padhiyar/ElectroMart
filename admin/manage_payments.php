<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

$result = $conn->query("SELECT payments.id, payments.order_id, payments.transaction_id, payments.payment_status, orders.total_price 
                        FROM payments 
                        JOIN orders ON payments.order_id = orders.id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Payments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/stylesheet.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="dashboard-container">
    <?php include 'dashboard-sidebar.php'; ?>

    <main class="vendor-main-content">
        <div class="container py-4">
            <h2 class="mb-4">Manage Payments</h2>

            <table class="table table-bordered table-striped align-middle table-manage-products">
                <thead class="table-dark">
                    <tr>
                        <th>Payment ID</th>
                        <th>Order ID</th>
                        <th>Transaction ID</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()):
                        $status = strtolower($row['payment_status']);
                        $badgeClass = match($status) {
                            'completed' => 'bg-success',
                            'pending' => 'bg-warning text-dark',
                            'failed' => 'bg-danger',
                            default => 'bg-secondary'
                        };
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td>#<?php echo htmlspecialchars($row['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['transaction_id']); ?></td>
                        <td>$<?php echo number_format($row['total_price'], 2); ?></td>
                        <td><span class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($row['payment_status']); ?></span></td>
                        <td>
                            <a href="update_payment.php?id=<?php echo $row['id']; ?>&status=completed" class="btn btn-sm btn-success me-1">Mark as Completed</a>
                            <a href="update_payment.php?id=<?php echo $row['id']; ?>&status=failed" class="btn btn-sm btn-danger">Mark as Failed</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
        </div>
    </main>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
