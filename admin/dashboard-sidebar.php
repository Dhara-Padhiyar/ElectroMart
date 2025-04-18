<?php
// Determine base path based on the file's location
$base_path = (strpos($_SERVER['PHP_SELF'], '/auth/') !== false) ? '../' : '';
?>

<aside class="dashboard-sidebar">
    <div class="sidebar-header">
        <h3><i class="fas fa-user-circle"></i> My Dashboard</h3>
    </div>
    
    <nav class="sidebar-nav">
        <div class="sidebar-section">
            <h4 class="sidebar-title">Manage</h4>
            <ul>
                <li><a href="<?php echo $base_path; ?>manage_users.php">Manage Users</a></li>
                <li><a href="<?php echo $base_path; ?>manage_vendors.php">Manage Vendors</a></li>
                <li><a href="<?php echo $base_path; ?>manage_orders.php">Manage Orders</a></li>
                <li><a href="<?php echo $base_path; ?>manage_payments.php">Manage Payments</a></li>
                <li><a href="<?php echo $base_path; ?>manage_categories.php">Manage Categories</a></li>
                <li><a href="<?php echo $base_path; ?>reviews.php">Manage Reviews</a></li>
                <li><a href="<?php echo $base_path; ?>sales_reports.php">Sales Reports</a></li>
                <li><a href="<?php echo $base_path; ?>site_settings.php">Site Settings</a></li>
            </ul>
        </div>
    </nav>
</aside>