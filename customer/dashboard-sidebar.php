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
        <!-- Account Management -->
        <div class="sidebar-section">
            <h4 class="sidebar-title">Account</h4>
            <ul>
                <li><a href="<?php echo $base_path; ?>profile.php"><i class="fas fa-user-edit"></i> Edit Profile</a></li>
                <li><a href="<?php echo $base_path; ?>password.php"><i class="fas fa-key"></i> Change Password</a></li>
                <li><a href="<?php echo $base_path; ?>order-history.php"><i class="fas fa-history"></i> Order History</a></li>
                <li><a href="<?php echo $base_path; ?>wishlist.php"><i class="fas fa-heart"></i> Wishlist</a></li>
            </ul>
        </div>
        
        <!-- Shopping Features -->
        <div class="sidebar-section">
            <h4 class="sidebar-title">Shopping</h4>
            <ul>
                <li><a href="<?php echo $base_path; ?>reviews.php"><i class="fas fa-star"></i> My Reviews</a></li>
                <li><a href="<?php echo $base_path; ?>compare.php"><i class="fas fa-exchange-alt"></i> Compare Products</a></li>
                <li><a href="<?php echo $base_path; ?>recently-viewed.php"><i class="fas fa-clock"></i> Recently Viewed</a></li>
            </ul>
        </div>
        
        <!-- Order Management -->
        <div class="sidebar-section">
            <h4 class="sidebar-title">Orders</h4>
            <ul>
                <li><a href="<?php echo $base_path; ?>orders.php"><i class="fas fa-truck"></i> Track Orders</a></li>
                <li><a href="<?php echo $base_path; ?>returns.php"><i class="fas fa-undo"></i> Returns & Cancellations</a></li>
            </ul>
        </div>
        
        <!-- Support -->
        <div class="sidebar-section">
            <h4 class="sidebar-title">Support</h4>
            <ul>
                <li><a href="<?php echo $base_path; ?>contact.php"><i class="fas fa-envelope"></i> Contact Support</a></li>
                <li><a href="<?php echo $base_path; ?>faq.php"><i class="fas fa-question-circle"></i> FAQs</a></li>
                <li><a href="<?php echo $base_path; ?>help-center.php"><i class="fas fa-life-ring"></i> Help Center</a></li>
            </ul>
        </div>
    </nav>
    </div>
</div>

<?php
// Determine base path based on the file's location
$base_path = (strpos($_SERVER['PHP_SELF'], '/auth/') !== false) ? '../' : '';
?>

<aside class="dashboard-sidebar">
    <div class="sidebar-header">
        <h3><i class="fas fa-user-circle"></i> My Dashboard</h3>
    </div>
    
    <nav class="sidebar-nav">
        <!-- Account Management -->
        <div class="sidebar-section">
            <h4 class="sidebar-title">Account</h4>
            <ul>
                <li><a href="<?php echo $base_path; ?>profile.php"><i class="fas fa-user-edit"></i> Edit Profile</a></li>
                <li><a href="<?php echo $base_path; ?>password.php"><i class="fas fa-key"></i> Change Password</a></li>
                <li><a href="<?php echo $base_path; ?>order-history.php"><i class="fas fa-history"></i> Order History</a></li>
                <li><a href="<?php echo $base_path; ?>wishlist.php"><i class="fas fa-heart"></i> Wishlist</a></li>
            </ul>
        </div>
        
        <!-- Shopping Features -->
        <div class="sidebar-section">
            <h4 class="sidebar-title">Shopping</h4>
            <ul>
                <li><a href="<?php echo $base_path; ?>reviews.php"><i class="fas fa-star"></i> My Reviews</a></li>
                <li><a href="<?php echo $base_path; ?>compare.php"><i class="fas fa-exchange-alt"></i> Compare Products</a></li>
                <li><a href="<?php echo $base_path; ?>recently-viewed.php"><i class="fas fa-clock"></i> Recently Viewed</a></li>
            </ul>
        </div>
        
        <!-- Order Management -->
        <div class="sidebar-section">
            <h4 class="sidebar-title">Orders</h4>
            <ul>
                <li><a href="<?php echo $base_path; ?>orders.php"><i class="fas fa-truck"></i> Track Orders</a></li>
                <li><a href="<?php echo $base_path; ?>returns.php"><i class="fas fa-undo"></i> Returns & Cancellations</a></li>
            </ul>
        </div>
        
        <!-- Support -->
        <div class="sidebar-section">
            <h4 class="sidebar-title">Support</h4>
            <ul>
                <li><a href="<?php echo $base_path; ?>contact.php"><i class="fas fa-envelope"></i> Contact Support</a></li>
                <li><a href="<?php echo $base_path; ?>faq.php"><i class="fas fa-question-circle"></i> FAQs</a></li>
                <li><a href="<?php echo $base_path; ?>help-center.php"><i class="fas fa-life-ring"></i> Help Center</a></li>
            </ul>
        </div>
    </nav>
</aside>