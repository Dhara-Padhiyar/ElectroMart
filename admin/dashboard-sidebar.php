<?php
// Determine base path based on the file's location
$base_path = (strpos($_SERVER['PHP_SELF'], '/auth/') !== false) ? '../' : '';
?>

<div class="mobile-sidebar-toggle d-md-none">
  <div class="hamburger" data-bs-toggle="offcanvas" data-bs-target="#adminSidebarMobile" aria-controls="adminSidebarMobile">
    <span></span>
    <span></span>
    <span></span>
  </div>
</div>


<!-- MOBILE Sidebar Offcanvas -->
<div class="offcanvas offcanvas-start bg-light" tabindex="-1" id="adminSidebarMobile">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title"><i class="fas fa-user-circle"></i> Menu </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <nav class="sidebar-nav">
            <div class="sidebar-section">
                <h4 class="sidebar-title">Manage</h4>
                <ul>
                    <li><a href="<?= $base_path; ?>manage_users.php">Manage Users</a></li>
                    <li><a href="<?= $base_path; ?>manage_vendors.php">Manage Vendors</a></li>
                    <li><a href="<?= $base_path; ?>manage_orders.php">Manage Orders</a></li>
                    <li><a href="<?= $base_path; ?>manage_payments.php">Manage Payments</a></li>
                    <li><a href="<?= $base_path; ?>manage_categories.php">Manage Categories</a></li>
                    <li><a href="<?= $base_path; ?>reviews.php">Manage Reviews</a></li>
                    <li><a href="<?= $base_path; ?>sales_reports.php">Sales Reports</a></li>
                    <li><a href="<?= $base_path; ?>site_settings.php">Site Settings</a></li>
                </ul>
            </div>
        </nav>
    </div>
</div>

<!-- DESKTOP Sidebar (Unchanged) -->
<aside class="dashboard-sidebar d-none d-md-block">
    <div class="sidebar-header">
        <h3><i class="fas fa-user-circle"></i> My Dashboard</h3>
    </div>

    <nav class="sidebar-nav">
        <div class="sidebar-section">
            <h4 class="sidebar-title">Manage</h4>
            <ul>
                <li><a href="<?= $base_path; ?>manage_users.php">Manage Users</a></li>
                <li><a href="<?= $base_path; ?>manage_vendors.php">Manage Vendors</a></li>
                <li><a href="<?= $base_path; ?>manage_orders.php">Manage Orders</a></li>
                <li><a href="<?= $base_path; ?>manage_payments.php">Manage Payments</a></li>
                <li><a href="<?= $base_path; ?>manage_categories.php">Manage Categories</a></li>
                <li><a href="<?= $base_path; ?>reviews.php">Manage Reviews</a></li>
                <li><a href="<?= $base_path; ?>sales_reports.php">Sales Reports</a></li>
                <li><a href="<?= $base_path; ?>site_settings.php">Site Settings</a></li>
            </ul>
        </div>
    </nav>
</aside>
