<?php
session_start();
include_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'vendor') {
    header("Location: ../auth/login.php");
    exit();
}

$vendor_id = null;
$store_name = '';
$approved = 0;

// Fetch vendor info
$get_vendor = $conn->prepare("SELECT id, store_name, approved FROM vendors WHERE user_id = ?");
if (!$get_vendor) {
    die("Query failed: " . $conn->error);
}
$get_vendor->bind_param("i", $_SESSION['user_id']);
$get_vendor->execute();
$get_vendor->bind_result($vendor_id, $store_name, $approved);
$get_vendor->fetch();
$get_vendor->close();

// Product count
$product_count = 0;
$stmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE vendor_id = ?");
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$stmt->bind_result($product_count);
$stmt->fetch();
$stmt->close();

// Order count
$order_count = 0;
$order_stmt = $conn->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?");
$order_stmt->bind_param("i", $_SESSION['user_id']);
$order_stmt->execute();
$order_stmt->bind_result($order_count);
$order_stmt->fetch();
$order_stmt->close();

// Review count & average rating
$review_count = 0;
$avg_rating = 0.0;
$review_stmt = $conn->prepare("SELECT COUNT(*), AVG(rating) FROM reviews WHERE vendor_id = ?");
$review_stmt->bind_param("i", $vendor_id);
$review_stmt->execute();
$review_stmt->bind_result($review_count, $avg_rating);
$review_stmt->fetch();
$review_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vendor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/stylesheet.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .dashboard-card {
            color: white;
            border: none;
            border-radius: 1rem;
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .dashboard-icon {
            font-size: 3rem;
            opacity: 0.2;
        }

        .products-card {
            background: linear-gradient(135deg, #0d6efd, #3a8ffd) !important;
        }

        .orders-card {
            background: linear-gradient(135deg, #198754, #33c48c) !important;
        }

        .reviews-card {
            background: linear-gradient(135deg, #ffc107, #ffda6a) !important;
            color: #212529;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
        }

        .stat-label {
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="dashboard-container">
    <?php include 'dashboard-sidebar.php'; ?>

    <main class="vendor-main-content">
        <div class="container py-4">
            <h2 class="mb-4">Welcome to your store <?= htmlspecialchars($store_name) ?></h2>

            <div class="mb-3">
                <strong>Status:</strong>
                <span class="badge bg-<?= $approved ? 'success' : 'warning' ?>">
                    <?= $approved ? 'Approved' : 'Pending' ?>
                </span>
            </div>

            <div class="row g-4 mt-4">
                <div class="col-md-4">
                    <div class="dashboard-card products-card">
                        <div>
                            <div class="stat-label">Total Products</div>
                            <div class="stat-number"><?= $product_count ?></div>
                        </div>
                        <i class="bi bi-box-seam dashboard-icon"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card orders-card">
                        <div>
                            <div class="stat-label">Total Orders</div>
                            <div class="stat-number"><?= $order_count ?></div>
                        </div>
                        <i class="bi bi-cart-check dashboard-icon"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card reviews-card">
                        <div>
                            <div class="stat-label">Reviews</div>
                            <div class="stat-number"><?= $review_count ?> 
                                <span class="fs-6 text-muted">| <?= number_format($avg_rating, 1) ?> ★</span>
                            </div>
                        </div>
                        <i class="bi bi-star-fill dashboard-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
