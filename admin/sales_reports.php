<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

// Get sales data
$total_sales = $conn->query("SELECT SUM(total_price) AS revenue FROM orders WHERE status='delivered'")->fetch_assoc();
$total_orders = $conn->query("SELECT COUNT(id) AS orders FROM orders WHERE status='delivered'")->fetch_assoc();
$total_customers = $conn->query("SELECT COUNT(DISTINCT user_id) AS customers FROM orders")->fetch_assoc();

// Sales by date for chart
$sales_by_date = $conn->query("SELECT DATE(created_at) as date, SUM(total_price) as revenue FROM orders WHERE status='delivered' GROUP BY DATE(created_at) ORDER BY date ASC");
$sales_data = [];
while ($row = $sales_by_date->fetch_assoc()) {
    $sales_data[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Analytics Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/stylesheet.css">
    <style>
        .stat-card {
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .chart-container {
            position: relative;
            height: 400px;
            margin-top: 2rem;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="dashboard-container">
    <?php include 'dashboard-sidebar.php'; ?>

    <main class="vendor-main-content">
        <div class="container py-4">
            <h2 class="mb-4">Sales Reports & Analytics</h2>

            <!-- Summary Cards -->
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card text-white bg-primary stat-card">
                        <div class="card-header">Total Revenue</div>
                        <div class="card-body">
                            <h5 class="card-title">$<?php echo number_format($total_sales["revenue"] ?? 0, 2); ?></h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success stat-card">
                        <div class="card-header">Total Orders</div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $total_orders["orders"] ?? 0; ?></h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-info stat-card">
                        <div class="card-header">Total Customers</div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $total_customers["customers"] ?? 0; ?></h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sales Chart -->
            <div class="chart-container">
                <canvas id="salesChart"></canvas>
            </div>

            <a href="dashboard.php" class="btn btn-secondary mt-4">Back to Dashboard</a>
        </div>
    </main>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [<?php foreach ($sales_data as $data) echo '"' . $data['date'] . '",'; ?>],
            datasets: [{
                label: 'Daily Revenue ($)',
                data: [<?php foreach ($sales_data as $data) echo $data['revenue'] . ','; ?>],
                backgroundColor: 'rgba(4, 85, 174, 0.2)',
                borderColor: 'rgba(4, 85, 174, 1)',
                borderWidth: 2,
                tension: 0.2,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
</body>
</html>
