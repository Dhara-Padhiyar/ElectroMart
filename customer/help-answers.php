<?php include 'header.php'; ?>
<div class="dashboard-container">
    <?php include 'dashboard-sidebar.php'; ?>
    <main class="profile-main-content">
        <h1 style="text-align: center; justify-content: center;"><i class="fas fa-book"></i> Help Center Answers</h1>

        <div class="faq-container">

            <section id="orders">
                <h2><i class="fas fa-shopping-bag"></i> Orders & Purchases</h2>
                <p>You can track your orders by logging into your account and going to the "My Orders" section. Each order will show its current status—processing, shipped, or delivered. You’ll receive email notifications with tracking details as your order progresses. If your order hasn’t shipped yet, you might be able to cancel or modify it. Always review your cart and shipping details before placing an order to ensure everything is correct.</p>
            </section>

            <section id="shipping">
                <h2><i class="fas fa-truck"></i> Shipping & Delivery</h2>
                <p>We provide multiple shipping options, including standard, expedited, and express delivery. Estimated delivery times and shipping costs are shown at checkout. Once your order is shipped, you’ll get an email with tracking information. You can also track shipments from your account dashboard. While we aim to deliver on time, occasional delays may occur due to weather, high demand, or courier issues.</p>
            </section>

            <section id="returns">
                <h2><i class="fas fa-undo"></i> Returns & Refunds</h2>
                <p>Most items can be returned within 30 days of delivery, provided they are unused and in their original packaging. To start a return, visit "My Orders", select the item, and choose "Return". After we receive and inspect your return, your refund will be processed within 5–7 business days. Return shipping fees may apply unless the return is due to a defect or shipping error. Refunds are issued to the original payment method.</p>
            </section>

            <section id="payments">
                <h2><i class="fas fa-credit-card"></i> Payments & Pricing</h2>
                <p>We accept all major credit/debit cards, PayPal, and other secure payment methods. Prices displayed on the website include applicable taxes and fees. During checkout, you can apply discount codes if available. Payment is processed through encrypted channels to ensure your data is secure. If a payment fails, please try an alternative method or contact your bank for support.</p>
            </section>

            <section id="account">
                <h2><i class="fas fa-user-cog"></i> Account Management</h2>
                <p>To manage your account, log in and navigate to "Account Settings". You can update your name, email address, password, and saved addresses. Keeping your account details up to date ensures smoother order processing and communication. If you forget your password, use the "Forgot Password" feature to reset it securely. For security reasons, we recommend changing your password regularly.</p>
            </section>

            <section id="products">
                <h2><i class="fas fa-box-open"></i> Product Information</h2>
                <p>Each product page includes detailed descriptions, specifications, images, and user reviews. This helps you make informed decisions based on features, compatibility, and customer experiences. Some products also have manuals or FAQs available for download. If you still need more information, our support team is ready to assist with recommendations or clarifications. Always read the full description before placing an order.</p>
            </section>

        </div>
    </main>
</div>
<?php include 'footer.php'; ?>

<!-- Highlighting style and smooth scroll -->
<style>
    html {
        scroll-behavior: smooth;
    }

    .faq-container {
        background: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        line-height: 1.8;
        margin-top: 20px;
    }

    .faq-container section {
        margin-bottom: 50px;
        padding: 15px;
        border-radius: 8px;
        transition: background-color 0.3s ease;
    }

    .faq-container h2 {
        font-size: 1.5rem;
        margin-bottom: 12px;
        color: #222;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .faq-container p {
        font-size: 1rem;
        color: #555;
        margin: 0;
        padding-left: 2px;
    }

    .profile-main-content h1 {
        margin-bottom: 25px;
        font-size: 2rem;
        color: #333;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .faq-container section:target {
        background-color: #e8f4ff;
        box-shadow: 0 0 0 3px #007bff33;
    }
</style>
