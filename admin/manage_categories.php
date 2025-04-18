<?php
session_start();
include "../config/db.php";

// Check admin authentication
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

// Handle adding new category securely
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["category_name"])) {
    $name = trim($_POST["category_name"]);
    if (!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->close();
        header("Location: manage_categories.php");
        exit;
    }
}

// Fetch categories securely
$stmt = $conn->prepare("SELECT * FROM categories ORDER BY id DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/stylesheet.css">
    <style>
         .input-group .form-control,
    .input-group .btn {
        height: 45px; /* adjust this to match Bootstrap's default input height */
        border-radius: 0; /* optional: to remove rounding between them */
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
            <h2 class="mb-4">Manage Categories</h2>

            <!-- Add Category Form -->
            <form method="POST" class="mb-4">
                <div class="input-group">
                    <input type="text" name="category_name" placeholder="New Category Name" required class="form-control">
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </div>
            </form>


            <!-- Categories Table -->
            <table class="table table-bordered table-striped align-middle table-manage-products">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Category Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row["id"]); ?></td>
                                <td><?php echo htmlspecialchars($row["name"]); ?></td>
                                <td>
                                    <a href="edit_category.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete_category.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">No categories found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
        </div>
    </main>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php 
$stmt->close();
$conn->close();
?>
