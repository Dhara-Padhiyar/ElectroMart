<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

$result = $conn->query("SELECT * FROM orders");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/stylesheet.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="dashboard-container">
    <?php include 'dashboard-sidebar.php'; ?>

    <main class="vendor-main-content">
        <div class="container py-4">
            <h2 class="mb-4">Manage Orders</h2>

            <table class="table table-bordered table-striped align-middle table-manage-products">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()):
                        $statusClass = strtolower($row['status']);
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                        <td>$<?php echo number_format($row['total_price'], 2); ?></td>
                        <td>
                            <span class="badge 
                                <?php 
                                    echo match($statusClass) {
                                        'pending' => 'bg-warning text-dark',
                                        'shipped' => 'bg-primary',
                                        'delivered' => 'bg-success',
                                        default => 'bg-secondary'
                                    }; 
                                ?>">
                                <?php echo htmlspecialchars($row['status']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="update_order.php?id=<?php echo $row['id']; ?>&status=shipped" class="btn btn-sm btn-primary me-1">Ship</a>
                            <a href="update_order.php?id=<?php echo $row['id']; ?>&status=delivered" class="btn btn-sm btn-success">Deliver</a>
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
