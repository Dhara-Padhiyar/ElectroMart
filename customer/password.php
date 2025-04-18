<?php 
session_start(); 
include '../config/db.php'; 

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('You must be logged in to change password.'); window.location.href='login.php';</script>";
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch current password hash from DB
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Verify current password
        if (password_verify($current_password, $hashed_password)) {
            if ($new_password === $confirm_password) {
                $new_hashed = password_hash($new_password, PASSWORD_DEFAULT);

                // Update password
                $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $update->bind_param("si", $new_hashed, $user_id);
                if ($update->execute()) {
                    echo "<script>alert('Password changed successfully.'); </script>";
                } else {
                    echo "<script>alert('Error updating password. Try again.');</script>";
                }
            } else {
                echo "<script>alert('New passwords do not match.');</script>";
            }
        } else {
            echo "<script>alert('Current password is incorrect.');</script>";
        }
    } else {
        echo "<script>alert('User not found.'); window.location.href='login.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="public/css/stylesheet.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="profile-container">
        <?php include 'dashboard-sidebar.php'; ?>

        <main class="profile-main-content">
            <h1><i class="fas fa-key"></i> Change Password</h1>

            <div class="password-container">
                <form class="password-form" method="post">
                    <div>
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>

                    <div>
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" required>
                        <div class="password-strength">
                            <span id="password-strength-bar"></span>
                        </div>
                    </div>

                    <div>
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                        <small id="password-match" style="color:red; display:none;">Passwords don't match!</small>
                    </div>

                    <button type="submit">Update Password</button>
                </form>
            </div>
        </main>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
