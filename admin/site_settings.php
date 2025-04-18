<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

$success = false;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST as $key => $value) {
        $stmt = $conn->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?");
        $stmt->bind_param("ss", $value, $key);
        $stmt->execute();
        $stmt->close();
    }
    $success = true;
}

// Fetch current settings
$result = $conn->query("SELECT * FROM site_settings");
$settings = [];
while ($row = $result->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Site Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/stylesheet.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="dashboard-container">
    <?php include 'dashboard-sidebar.php'; ?>

    <main class="vendor-main-content">
        <div class="container py-4">
            <h2 class="mb-4">Site Settings</h2>

            <?php if ($success): ?>
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <i class="fas fa-check-circle me-2"></i> Settings updated successfully!
                </div>
            <?php endif; ?>

            <form method="POST" class="row g-3">
                <div class="col-md-6">
                    <label for="site_name" class="form-label">Site Name</label>
                    <input type="text" class="form-control" id="site_name" name="site_name" required value="<?php echo htmlspecialchars($settings['site_name'] ?? ''); ?>">
                </div>

                <div class="col-md-6">
                    <label for="currency" class="form-label">Currency</label><br/>
                    <select id="currency" name="currency">
                        <option value="USD" <?php echo ($settings['currency'] == 'USD') ? 'selected' : ''; ?>>USD ($)</option>
                        <option value="EUR" <?php echo ($settings['currency'] == 'EUR') ? 'selected' : ''; ?>>EUR (€)</option>
                        <option value="GBP" <?php echo ($settings['currency'] == 'GBP') ? 'selected' : ''; ?>>GBP (£)</option>
                        <option value="INR" <?php echo ($settings['currency'] == 'INR') ? 'selected' : ''; ?>>INR (₹)</option>
                        <option value="JPY" <?php echo ($settings['currency'] == 'JPY') ? 'selected' : ''; ?>>JPY (¥)</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="payment_gateway" class="form-label">Payment Gateway</label>
                    <select class="form-select" id="payment_gateway" name="payment_gateway">
                        <option value="PayPal" <?php echo ($settings['payment_gateway'] ?? '') === 'PayPal' ? 'selected' : ''; ?>>PayPal</option>
                        <option value="Stripe" <?php echo ($settings['payment_gateway'] ?? '') === 'Stripe' ? 'selected' : ''; ?>>Stripe</option>
                    </select>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Settings
                    </button>
                    <a href="dashboard.php" class="btn btn-secondary ms-2">
                        <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                    </a>
                </div>
            </form>
        </div>
    </main>
</div>

<?php include 'footer.php'; ?>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
