<?php
session_start();
include_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: ../auth/login.php");
    exit();
}

$customer_name = '';
$order_count = 0;
$review_count = 0;
$wishlist_count = 0;

// Fetch customer name
$customer_stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$customer_stmt->bind_param("i", $_SESSION['user_id']);
$customer_stmt->execute();
$customer_stmt->bind_result($customer_name);
$customer_stmt->fetch();
$customer_stmt->close();

// Count orders
$order_stmt = $conn->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?");
$order_stmt->bind_param("i", $_SESSION['user_id']);
$order_stmt->execute();
$order_stmt->bind_result($order_count);
$order_stmt->fetch();
$order_stmt->close();

// Count reviews
$review_stmt = $conn->prepare("SELECT COUNT(*) FROM reviews WHERE user_id = ?");
$review_stmt->bind_param("i", $_SESSION['user_id']);
$review_stmt->execute();
$review_stmt->bind_result($review_count);
$review_stmt->fetch();
$review_stmt->close();

// Count wishlist items
$wishlist_stmt = $conn->prepare("SELECT COUNT(*) FROM wiselist WHERE customerid = ?");
if (!$wishlist_stmt) {
    die("Prepare failed for wishlist query: " . $conn->error);
}
$wishlist_stmt->bind_param("i", $_SESSION['user_id']);
$wishlist_stmt->execute();
$wishlist_stmt->bind_result($wishlist_count);
$wishlist_stmt->fetch();
$wishlist_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/stylesheet.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .cd-card {
            color: white;
            border-radius: 1rem;
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .cd-icon {
            font-size: 3rem;
            opacity: 0.2;
        }

        .cd-orders {
            background: linear-gradient(135deg, #004aad, #007bff);
        }

        .cd-reviews {
            background: linear-gradient(135deg, #ffcc00, #ffe066);
            color: #212529;
        }

        .cd-wishlist {
            background: linear-gradient(135deg, #4caf50, #2e7d32);
            color: #fff;

        }

        .cd-label {
            font-size: 1.2rem;
        }

        .cd-number {
            font-size: 2.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="dashboard-container">
    <?php include 'dashboard-sidebar.php'; ?>

    <main class="vendor-main-content">
        <div class="container py-4">
            <h2 class="mb-4">Welcome back, <?= htmlspecialchars($customer_name) ?>!</h2>

            <div class="row g-4 mt-4">
                <div class="col-md-4">
                    <div class="cd-card cd-orders">
                        <div>
                            <div class="cd-label">Your Orders</div>
                            <div class="cd-number"><?= $order_count ?></div>
                        </div>
                        <i class="bi bi-bag-check cd-icon"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="cd-card cd-reviews">
                        <div>
                            <div class="cd-label">Your Reviews</div>
                            <div class="cd-number"><?= $review_count ?></div>
                        </div>
                        <i class="bi bi-star cd-icon"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="cd-card cd-wishlist">
                        <div>
                            <div class="cd-label">Wishlist Items</div>
                            <div class="cd-number"><?= $wishlist_count ?></div>
                        </div>
                        <i class="bi bi-heart cd-icon"></i>
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
