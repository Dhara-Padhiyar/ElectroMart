<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Determine base path based on the file's location
$base_path = (strpos($_SERVER['PHP_SELF'], '/auth/') !== false) ? '../' : '';
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ElectroMart – Powering Your Digital World!</title>

    <!-- Dynamic CSS Path -->
    <link rel="stylesheet" href="<?php echo $base_path; ?>public/css/stylesheet.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Raleway&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div id="fff-pagecontainer">
    <header>
        <div class="fff-offer"><p>ElectroMart – Powering Your Digital World!</p></div>
        <div class="fff-logo">
            <a href="<?php echo $base_path; ?>index.php">
                <img src="<?php echo $base_path; ?>../images/logo.png" alt="ElectroMart-logo">
            </a>
        </div>
        <nav class="fff-nav">
            <ul>
                <li><a href="<?php echo $base_path; ?>index.php">Home</a></li>
                <li><a href="<?php echo $base_path; ?>vendor/dashboard.php">Dashboard</a></li>
                <li><a href="<?php echo $base_path; ?>product.php">Product</a></li>
                <li><a href="<?php echo $base_path; ?>about.php">About</a></li>
                <li><a href="<?php echo $base_path; ?>contact.php">Contact</a></li>

                <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'vendor'): ?>
                    <li><a href="<?php echo $base_path; ?>auth/logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="<?php echo $base_path; ?>auth/auth.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
