<?php
include '../config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch all reviews with product, vendor, and customer info
$query = "
    SELECT r.rating, r.review_text, r.created_at, 
           u.name AS customer_name, 
           p.name AS product_name, 
           v.name AS vendor_name
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    JOIN products p ON r.product_id = p.id
    JOIN users v ON r.vendor_id = v.id
    ORDER BY r.created_at DESC
";

$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Reviews - Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/stylesheet.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="dashboard-container">
    <?php include 'dashboard-sidebar.php'; ?>

    <main class="vendor-main-content">
        <div class="container py-4">
            <h2 class="mb-4">All Customer Reviews</h2>

            <table class="table table-bordered table-striped align-middle table-manage-products">
                <thead class="table-dark">
                    <tr>
                        <th>Product</th>
                        <th>Vendor</th>
                        <th>Customer</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['product_name']) ?></td>
                            <td><?= htmlspecialchars($row['vendor_name']) ?></td>
                            <td><?= htmlspecialchars($row['customer_name']) ?></td>
                            <td class="text-warning"><?= $row['rating'] ?> ★</td>
                            <td><?= htmlspecialchars($row['review_text']) ?></td>
                            <td><?= $row['created_at'] ?></td>
                        </tr>
                    <?php endwhile; ?>

                    <?php if ($result->num_rows === 0): ?>
                        <tr>
                            <td colspan="6">No reviews found.</td>
                        </tr>
                    <?php endif; ?>
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
