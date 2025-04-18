<?php
include '../config/db.php';
session_start();

// Vendor authentication
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'vendor') {
    header("Location: ../auth/login.php");
    exit();
}

$vendor_id = $_SESSION['user_id'];

// Fetch all reviews for this vendor's products
$stmt = $conn->prepare("
    SELECT r.rating, r.review_text, r.created_at, u.name AS customer_name, p.name AS product_name
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    JOIN products p ON r.product_id = p.id
    WHERE r.vendor_id = ?
    ORDER BY r.created_at DESC
");
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Reviews</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/stylesheet.css">
    <style>
        .reviews-wrapper {
            padding: 40px;
            background: #f7f7f7;
            font-family: Arial, sans-serif;
        }

        .reviews-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }

        .reviews-table th, .reviews-table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .reviews-table th {
            background-color: #2e86de;
            color: #fff;
        }

        .reviews-heading {
            margin-bottom: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="dashboard-container">
    <?php include 'dashboard-sidebar.php'; ?>

    <main class="vendor-main-content">
        <div class="container-fluid reviews-wrapper">
            <h2 class="reviews-heading">Customer Reviews on Your Products</h2>

            <table class="reviews-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Customer</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['product_name']) ?></td>
                            <td><?= htmlspecialchars($row['customer_name']) ?></td>
                            <td><?= $row['rating'] ?> ★</td>
                            <td><?= htmlspecialchars($row['review_text']) ?></td>
                            <td><?= $row['created_at'] ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No reviews found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
