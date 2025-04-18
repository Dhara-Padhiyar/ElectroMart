<?php
session_start();
include_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'vendor') {
    header("Location: ../auth/login.php");
    exit();
}

$error = '';
$success = '';
$user_id = $_SESSION['user_id'];

// Verify vendor exists in vendors table
$check_vendor = $conn->prepare("SELECT id FROM vendors WHERE user_id = ?");
$check_vendor->bind_param("i", $user_id);
$check_vendor->execute();
$check_vendor->store_result();

if ($check_vendor->num_rows > 0) {
    $check_vendor->bind_result($vendor_id);
    $check_vendor->fetch();
} else {
    $error = "Your vendor account is not properly registered. Please contact support.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($error)) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $features = trim($_POST['features']);
    $price = floatval($_POST['price']);
    $category = intval($_POST['category']);
    $stock = intval($_POST['stock']);

    // Additional Validations
    if (empty($name) || empty($description) || $price <= 0 || $stock < 0) {
        $error = "Please fill all fields with valid data";
    } elseif ($stock > 50) {
        $error = "Stock quantity cannot be more than 50 units";
    } else {
        // Check if product name is already used by this vendor
        $check_name = $conn->prepare("SELECT id FROM products WHERE vendor_id = ? AND name = ?");
        $check_name->bind_param("is", $vendor_id, $name);
        $check_name->execute();
        $check_name->store_result();

        if ($check_name->num_rows > 0) {
            $error = "You already have a product with this name. Please use a unique product name.";
        } else {
            if (!empty($_FILES['image']['name'])) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                $max_size = 2 * 1024 * 1024;

                if (!in_array($_FILES['image']['type'], $allowed_types)) {
                    $error = "Only JPG, PNG, and GIF images are allowed";
                } elseif ($_FILES['image']['size'] > $max_size) {
                    $error = "Image size must be less than 2MB";
                } else {
                    $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    $image_name = uniqid('product_', true) . '.' . $image_ext;
                    $upload_path = '../images/' . $image_name;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                        $stmt = $conn->prepare("INSERT INTO products (vendor_id, name, description, Features, price, category, stock, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("issdiiss", $vendor_id, $name, $description, $features, $price, $category, $stock, $image_name);

                        if ($stmt->execute()) {
                            $success = "Product added successfully!";
                        } else {
                            $error = "Error saving product: " . $conn->error;
                            if (file_exists($upload_path)) {
                                unlink($upload_path);
                            }
                        }
                    } else {
                        $error = "Error uploading image";
                    }
                }
            } else {
                $error = "Product image is required";
            }
        }
        $check_name->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product - Vendor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/stylesheet.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="dashboard-container">
    <?php include 'dashboard-sidebar.php'; ?>

    <main class="vendor-main-content">
        <div class="card p-4">
            <h2 class="mb-4">Add New Product</h2>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Price ($)</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="features" class="form-label">Features</label>
                    <textarea class="form-control" id="features" name="features" rows="2" placeholder="e.g., Waterproof, 4K Display, Bluetooth enabled"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select name="category" id="category" class="form-control" required>
                            <option value="">-- Select Category --</option>
                            <?php
                            $cat_query = $conn->query("SELECT id, name FROM categories");
                            if ($cat_query && $cat_query->num_rows > 0) {
                                while ($row = $cat_query->fetch_assoc()) {
                                    echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</option>';
                                }
                            } else {
                                echo '<option value="">No categories available</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label">Stock Quantity</label>
                        <input type="number" class="form-control" id="stock" name="stock" required max="50">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="image" class="form-label">Product Image</label>
                    <input class="form-control" type="file" id="image" name="image" accept="image/jpeg, image/png, image/gif" required>
                    <div class="form-text">Max file size: 2MB (JPEG, PNG, GIF only)</div>
                </div>

                <div class="d-flex justify-content-start gap-2 mt-3">
                    <button type="submit" class="btn btn-sm btn-primary">Add Product</button>
                    <a href="dashboard.php" class="btn btn-sm btn-outline-secondary">Back to Dashboard</a>
                </div>

            </form>
        </div>
    </main>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
