<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

$admin_name = 'Admin';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/stylesheet.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .ad-card {
            color: white;
            border-radius: 1rem;
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .ad-icon {
            font-size: 3rem;
            opacity: 0.2;
        }

        .ad-users {
            background: linear-gradient(135deg, #004aad, #007bff);
        }

        .ad-orders {
            background: linear-gradient(135deg, #ffcc00, #ffe066);
            color: #212529;
        }

        .ad-settings {
            background: linear-gradient(135deg, #4caf50, #2e7d32);
            color: #fff;
        }

        .ad-label {
            font-size: 1.2rem;
        }

        .ad-number {
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
            <h2 class="mb-4">Welcome back, <?= htmlspecialchars($admin_name) ?>!</h2>

            <div class="row g-4 mt-4">
                <div class="col-md-4">
                    <div class="ad-card ad-users">
                        <div>
                            <div class="ad-label">Manage Users</div>
                            <div class="ad-number"><i class="bi bi-people"></i></div>
                        </div>
                        <i class="bi bi-person-gear ad-icon"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="ad-card ad-orders">
                        <div>
                            <div class="ad-label">Manage Orders</div>
                            <div class="ad-number"><i class="bi bi-bag-check"></i></div>
                        </div>
                        <i class="bi bi-cart-check ad-icon"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="ad-card ad-settings">
                        <div>
                            <div class="ad-label">Site Settings</div>
                            <div class="ad-number"><i class="bi bi-gear"></i></div>
                        </div>
                        <i class="bi bi-sliders ad-icon"></i>
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
