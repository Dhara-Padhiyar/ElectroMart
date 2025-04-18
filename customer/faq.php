<?php
session_start();
include '../config/db.php';

// Example static data - could be customized per vendor
$faqs = [
    [
        'question' => 'How do I track my order?',
        'answer' => 'You can track your order by visiting the "My Orders" section under your account dashboard.',
        'category' => 'Orders',
    ],
    [
        'question' => 'What is your return policy?',
        'answer' => 'Returns are accepted within 30 days of delivery. Items must be unused and in original condition.',
        'category' => 'Returns',
    ],
    [
        'question' => 'Which payment methods are accepted?',
        'answer' => 'We accept Visa, MasterCard, PayPal, and other major payment options.',
        'category' => 'Payments',
    ],
    [
        'question' => 'Can I update my account details?',
        'answer' => 'Yes, go to Account Settings to change your email, password, or shipping information.',
        'category' => 'Account',
    ],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FAQs - ElectroMart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Styles -->
    <link rel="stylesheet" href="public/css/stylesheet.css"> <!-- path unchanged as per your setup -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="profile-container">
    <?php include 'dashboard-sidebar.php'; ?>

    <main class="profile-main-content">
        <h1><i class="fas fa-question-circle"></i> Frequently Asked Questions</h1>

        <div class="faq-container">
            <?php foreach ($faqs as $faq): ?>
                <div class="faq-item">
                    <div class="faq-question">
                        <span><?= htmlspecialchars($faq['question']) ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <?= nl2br(htmlspecialchars($faq['answer'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>

<?php include 'footer.php'; ?>

<!-- FAQ Toggle Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const faqItems = document.querySelectorAll('.faq-item');

        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question');
            const answer = item.querySelector('.faq-answer');
            const icon = question.querySelector('i');

            question.addEventListener('click', () => {
                answer.classList.toggle('active');
                icon.classList.toggle('rotated');
            });
        });
    });
</script>



<!-- FAQ Styles to match dashboard -->
<style>
    .faq-answer {
    display: none;
    padding-top: 10px;
    color: #555;
    line-height: 1.5;
}

.faq-answer.active {
    display: block;
}

.faq-question i {
    transition: transform 0.3s ease;
}

.faq-question i.rotated {
    transform: rotate(180deg);
}

</style>
</body>
</html>
