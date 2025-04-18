<?php
session_start();
include "../config/db.php";

// Admin authentication
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

// Ensure category ID is provided
if (!isset($_GET["id"])) {
    header("Location: manage_categories.php");
    exit;
}

$id = $_GET["id"];
$result = $conn->query("SELECT * FROM categories WHERE id='$id'");
$category = $result->fetch_assoc();

// Handle update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = trim($_POST["category_name"]);
    if (!empty($new_name)) {
        $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $new_name, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: manage_categories.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/stylesheet.css">
    <style>
        .input-group .form-control,
        .input-group .btn {
            height: 45px;
            border-radius: 0;
        }

        .input-group .form-control {
            border-top-left-radius: 0.375rem;
            border-bottom-left-radius: 0.375rem;
        }

        .input-group .btn {
            border-top-right-radius: 0.375rem;
            border-bottom-right-radius: 0.375rem;
            margin: 0;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="dashboard-container">
    <?php include 'dashboard-sidebar.php'; ?>

    <main class="vendor-main-content">
        <div class="container py-4">
            <h2 class="mb-4">Edit Category</h2>
            <form method="POST" class="mb-3">
                <div class="input-group">
                    <input type="text" name="category_name" value="<?php echo htmlspecialchars($category['name']); ?>" required class="form-control">
                    <button type="submit" class="btn btn-success">Update Category</button>
                </div>
            </form>
            <a href="manage_categories.php" class="btn btn-secondary mt-2">Back</a>
        </div>
    </main>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
