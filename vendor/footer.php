<?php
    $base_path = (strpos($_SERVER['PHP_SELF'], '/auth/') !== false) ? '../' : '';
?>
<footer>
    <div class="footer-main">
        <div class="footer-division">
        <img src="<?php echo $base_path; ?>../images/logo.png" class="footer-logo-img" alt="ElectroMart-footer-logo">
        <p>From mobile phones to home appliances, we’ve got everything tech. Experience reliable service and unbeatable prices at ElectroMart.</p>
        </div>
        <div class="footer-division">
            <h3>Store hours</h3>
            <ul>
                <li>Monday 9:00am to 9:00pm</li>
                <li>Tuesday 9:00am to 1:00pm</li>
                <li>Wednesday 9:00am to 1:00pm</li>
                <li>Thursday 9:00am to 1:00pm</li>
                <li>Friday 9:00am to 1:00pm</li>
                <li>Sat-Sunday 9:00am to 1:00pm</li>
            </ul>
        </div>
        <div class="footer-division">
            <h3>Locations</h3>
            <ul>
                <li>Calgary</li>
                <li>Winnipeg</li>
                <li>Victoria</li>
                <li>Vancouver</li>
                <li>Toronto</li>
            </ul>
        </div>
        <div class="footer-division"> 
            <h3>Quick links</h3>
            <nav>
                <ul>
                    <li><a href="index.php">home</a></li>
                    <li><a href="product.php">products</a></li>
                    <li><a href="about.php">about us</a></li>
                    <li><a href="contact.php">contact</a></li>
                </ul>
            </nav>
        </div>
    </div>
    <p id="copyright">&copy; 2024 ElectroMart</p>
</footer>
</div>
</body>
</html>
