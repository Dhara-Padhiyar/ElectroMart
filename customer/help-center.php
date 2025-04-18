<?php 
// Sample help topics with updated links to anchors in help-answers.php
$help_topics = [
    [
        'title' => 'Orders & Purchases',
        'icon' => 'shopping-bag',
        'description' => 'Track orders, manage purchases, and understand order status',
        'link' => 'help-answers.php#orders'
    ],
    [
        'title' => 'Shipping & Delivery',
        'icon' => 'truck',
        'description' => 'Shipping options, delivery times, and tracking information',
        'link' => 'help-answers.php#shipping'
    ],
    [
        'title' => 'Returns & Refunds',
        'icon' => 'undo',
        'description' => 'Return policies, refund processes, and exchanges',
        'link' => 'help-answers.php#returns'
    ],
    [
        'title' => 'Payments & Pricing',
        'icon' => 'credit-card',
        'description' => 'Payment methods, pricing questions, and discounts',
        'link' => 'help-answers.php#payments'
    ],
    [
        'title' => 'Account Management',
        'icon' => 'user-cog',
        'description' => 'Password changes, profile updates, and account security',
        'link' => 'help-answers.php#account'
    ],
    [
        'title' => 'Product Information',
        'icon' => 'box-open',
        'description' => 'Product details, specifications, and compatibility',
        'link' => 'help-answers.php#products'
    ]
];

// Sample popular articles
$popular_articles = [
    [
        'title' => 'How to Track Your Order',
        'description' => 'Step-by-step guide to tracking your package'
    ],
    [
        'title' => 'Returning an Item',
        'description' => 'Complete instructions for returning products'
    ],
    [
        'title' => 'Changing Your Password',
        'description' => 'How to update your account password'
    ],
    [
        'title' => 'Understanding Shipping Options',
        'description' => 'Compare our different shipping methods'
    ],
    [
        'title' => 'Payment Method Options',
        'description' => 'Learn about all available payment options'
    ],
    [
        'title' => 'Creating a Wishlist',
        'description' => 'How to save items for later purchase'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Center</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/stylesheet.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="dashboard-container">
    <?php include 'dashboard-sidebar.php'; ?>

    <main class="profile-main-content">
        <h1><i class="fas fa-life-ring"></i> Help Center</h1>

        <div class="help-center-container">
            <div class="help-search">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="How can we help you today?">
            </div>

            <h3>Browse Help Topics</h3>
            <div class="help-categories">
                <?php foreach ($help_topics as $topic): ?>
                    <div class="help-category">
                        <div class="help-category-icon">
                            <i class="fas fa-<?php echo $topic['icon']; ?>"></i>
                        </div>
                        <div class="help-category-title"><?php echo $topic['title']; ?></div>
                        <div class="help-category-desc"><?php echo $topic['description']; ?></div>
                        <a href="<?php echo $topic['link']; ?>" class="help-category-link">
                            Learn More <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="help-contact">
                <h3>Still need help?</h3>
                <p>Can't find what you're looking for? Our support team is ready to assist you.</p>
                <a href="support.php" class="btn btn-primary">
                    <i class="fas fa-envelope"></i> Contact Support
                </a>
            </div>
        </div>
    </main>
</div>

<?php include 'footer.php'; ?>

<script>
    // Search input (Enter key)
    document.querySelector('.help-search input').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            const searchTerm = this.value.trim();
            if (searchTerm) {
                window.location.href = `help-answers.php?search=${encodeURIComponent(searchTerm)}`;
            }
        }
    });

    // Clickable popular articles (demo functionality)
    document.querySelectorAll('.popular-article').forEach(article => {
        article.style.cursor = 'pointer';
        article.addEventListener('click', function () {
            const title = this.querySelector('.popular-article-title').textContent;
            alert(`In a complete implementation, this would open the article: "${title}"`);
        });
    });
</script>
</body>
</html>
