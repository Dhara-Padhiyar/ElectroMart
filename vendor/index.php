<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fit Flex Fitness</title>
    <link rel="stylesheet" href="public/css/stylesheet.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Raleway&display=swap">
</head>
<body>
    <div id="fff-pagecontainer">
	<?php include 'views/header.php'; ?>    
        <main>
           <a href="/auth/auth.php" id="fff-hero-btn">Login</a>
            <img id="fff-heroimg" class="desktop" src="images/hero-image.jpg" alt="welcome-image"/>
            <div id="wrapper">
                <section class="fff-services">
                    <div class="service">
                        <div class="service-icon"><img src="images/quality-assurance.png" alt="quality-assurance-service-icon"/></div>
                        <div>
                            <p class="service-description">Quality Assurance</p>
                            <p>We have best quality products<br/>provides hi-tech mechanism</p>
                        </div>
                    </div>
                    <div class="service">
                        <div class="service-icon"><img src="images/technical-support.png" alt="technical-support-service-icon"/></div>
                        <div>
                            <p class="service-description">Technical Support</p>
                            <p>we have team of expert technicians<br/>to provide 24x7 support</p>
                        </div>
                    </div>
                    <div class="service">
                        <div class="service-icon"><img src="images/gadgets.png" alt="gedgets-service-icon"/></div>
                        <div>
                            <p class="service-description">Hi-Tech Gadgets</p>
                            <p>We have Hi-Tech products<br/>for all your needs</p>
                        </div>
                    </div>
                </section>
                <section class="fff-new-equipments">
                    <h1 class="heading">Our New Gadgets</h1>
                    <div class="equipments">
                        <a href="#">
                            <div class="equipment">
                                <div class="equipent-img">
                                    <img src="images/equipment-1.png" alt="bench-press-equipment"/>
                                </div>
                                <div class="equipment-desc">
                                    <p>Laptop</p>
                                </div>
                            </div>
                        </a>
                        <a href="#">
                            <div class="equipment">
                                <div class="equipent-img">
                                    <img src="images/equipment-2.png" alt="lat-pulldown-machine-equipment"/>
                                </div>
                                <div class="equipment-desc">
                                    <p>Watch</p>
                                </div>
                            </div>
                        </a>
                        <a href="#">
                            <div class="equipment">
                                <div class="equipent-img">
                                    <img src="images/equipment-3.png" alt="elliptical-trainer-equipment"/>
                                </div>
                                <div class="equipment-desc">
                                    <p>Television</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </section>
                <a href="product.php"><img id="fff-heroimg" class="desktop" src="images/home-page-second-banner.jpg" alt="welcome-image"/></a>
            </div>
        </main>
        <?php include 'views/footer.php'; ?>
    </div>
</body>
</html>

