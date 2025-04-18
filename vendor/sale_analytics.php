<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "vendor") {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales & Analytics | Vendor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/stylesheet.css">
</head>
<body>
    <div class="container">
        <header class="dashboard-header">
            <h2>Sales & Analytics</h2>
            <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
        </header>

       

        <main class="dashboard-content">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Sales</h5>
                            <p class="card-text h4">$12,345.67</p>
                            <p class="card-text"><small>Last 30 days</small></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Orders</h5>
                            <p class="card-text h4">142</p>
                            <p class="card-text"><small>Last 30 days</small></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Conversion Rate</h5>
                            <p class="card-text h4">3.2%</p>
                            <p class="card-text"><small>From visits to orders</small></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Sales Report</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <select class="form-select" style="width: 200px;">
                            <option>Last 7 days</option>
                            <option selected>Last 30 days</option>
                            <option>Last 90 days</option>
                            <option>This year</option>
                        </select>
                    </div>
                    <canvas id="salesChart" height="300"></canvas>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Top Selling Products</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Sales</th>
                                        <th>Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Premium Headphones</td>
                                        <td>45</td>
                                        <td>$4,500</td>
                                    </tr>
                                    <tr>
                                        <td>Wireless Earbuds</td>
                                        <td>32</td>
                                        <td>$2,880</td>
                                    </tr>
                                    <tr>
                                        <td>Bluetooth Speaker</td>
                                        <td>28</td>
                                        <td>$1,960</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Customer Feedback</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3">
                                    <span class="text-warning">★★★★★</span>
                                    <span class="text-muted">4.8/5.0</span>
                                </div>
                                <div>Average Rating</div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>5 stars</span>
                                    <span>85%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-success" style="width: 85%"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>4 stars</span>
                                    <span>10%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-info" style="width: 10%"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>3 stars</span>
                                    <span>3%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" style="width: 3%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Sales chart initialization
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Sales ($)',
                    data: [1200, 1900, 1500, 2000, 2500, 2200, 3000],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>