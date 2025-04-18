<?php
session_start();
include '../config/db.php';

// Fetch compared products from session
$compare_products = [];

if (!empty($_SESSION['compare'])) {
    $ids = implode(',', array_map('intval', $_SESSION['compare']));
    $sql = "SELECT p.*, c.name AS category FROM products p 
            LEFT JOIN categories c ON p.category = c.id 
            WHERE p.id IN ($ids)";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $compare_products[] = $row;
    }
}

// Remove a product from compare
if (isset($_GET['remove'])) {
    $remove_id = (int) $_GET['remove'];
    $_SESSION['compare'] = array_filter($_SESSION['compare'], fn($id) => $id != $remove_id);
    header("Location: compare.php");
    exit;
}

// Clear all compared products
if (isset($_GET['clear'])) {
    unset($_SESSION['compare']);
    header("Location: compare.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Compare Products</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="compare-content container mt-5">
    <h2 class="mb-4"><i class="bi bi-columns-gap"></i> Product Comparison</h2>

    <?php if (!empty($compare_products)): ?>
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Feature</th>
                        <?php foreach ($compare_products as $p): ?>
                            <th>
                                <img src="../images/<?php echo $p['image']; ?>" width="100" alt="<?php echo $p['name']; ?>"><br>
                                <strong><?php echo $p['name']; ?></strong><br>
                                ₹<?php echo $p['price']; ?><br>
                                <a href="?remove=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-danger mt-2">
                                    <i class="bi bi-x-circle"></i> Remove
                                </a>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Category</th>
                        <?php foreach ($compare_products as $p): ?>
                            <td><?php echo htmlspecialchars($p['category']); ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <?php foreach ($compare_products as $p): ?>
                            <td><?php echo substr(htmlspecialchars($p['description']), 0, 80); ?>...</td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="text-end">
            <a href="?clear=1" class="btn btn-outline-danger">
                <i class="bi bi-trash"></i> Clear All Compared
            </a>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No products added to compare yet.<br>
            <a href="product.php" class="btn btn-primary mt-3"><i class="bi bi-plus-circle"></i> Browse Products</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
</body>
</html>