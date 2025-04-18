<?php
session_start();
include_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'vendor') {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "No product ID specified.";
    exit();
}

$product_id = $_GET['id'];

// Get vendor ID
$get_vendor = $conn->prepare("SELECT id FROM vendors WHERE user_id = ?");
$get_vendor->bind_param("i", $_SESSION['user_id']);
$get_vendor->execute();
$get_vendor->store_result();
$get_vendor->bind_result($vendor_id);
$get_vendor->fetch();
$get_vendor->close();

// Fetch product data
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND vendor_id = ?");
$stmt->bind_param("ii", $product_id, $vendor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "Product not found or access denied.";
    exit();
}

$product = $result->fetch_assoc();

// Update logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $features = $_POST['features'];
    $description = $_POST['description'];

    if (!empty($_FILES['image']['name'])) {
        $image_name = basename($_FILES["image"]["name"]);
        $target_dir = "../images/";
        $target_file = $target_dir . $image_name;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    } else {
        $image_name = $product['image'];
    }

    $update = $conn->prepare("UPDATE products SET name=?, price=?, stock=?, Features=?, description=?, image=? WHERE id=? AND vendor_id=?");
    $update->bind_param("sdisssii", $name, $price, $stock, $features, $description, $image_name, $product_id, $vendor_id);

    if ($update->execute()) {
        header("Location: dashboard.php?updated=true");
        exit();
    } else {
        $error = "Error updating product.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product | Vendor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/stylesheet.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="dashboard-container">
    <?php include 'dashboard-sidebar.php'; ?>

    <main class="vendor-main-content">
        <div class="card p-4">
            <h2 class="mb-4">Edit Product</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Price</label>
                    <input type="number" name="price" step="0.01" class="form-control" value="<?= htmlspecialchars($product['price']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control" value="<?= $product['stock'] ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Features</label>
                    <textarea name="features" class="form-control" required><?= htmlspecialchars($product['Features']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" required><?= htmlspecialchars($product['description']) ?></textarea>
                </div>

                <div class="mb-3 image-thumbnail-block">
                    <label class="form-label">Change Image (optional)</label><br>
                    <img src="../images/<?= $product['image'] ?>" width="100" class="mb-2"><br>
                    <input type="file" name="image" class="form-control">
                </div>

                <button type="submit" class="btn btn-success">Update Product</button>
                <a href="vendor_dashboard.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </main>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
