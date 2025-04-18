<?php
include '../config/db.php';
session_start();

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$product_id = (int)$_GET['id'];
$product = $conn->query("SELECT p.*, c.name as category_name FROM products p 
                        LEFT JOIN categories c ON p.category = c.id 
                        WHERE p.id = $product_id")->fetch_assoc();

if (!$product) {
    header("Location: products.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - ElectroMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .profile-main-content.container.mt-5 {
            margin: 60px auto !important;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
    
    <div class="container mt-5 profile-main-content">
        <div class="row">
            <div class="col-md-6">
                <?php if (!empty($product['image']) && file_exists("../images/" . $product['image'])): ?>
                    <img src="../images/<?php echo htmlspecialchars($product['image']); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($product['name']); ?>">
                <?php else: ?>
                    <div class="bg-secondary text-white text-center p-5 rounded" style="height: 400px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-image" style="font-size: 5rem;"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                <?php if (!empty($product['category_name'])): ?>
                    <span class="badge bg-primary"><?php echo htmlspecialchars($product['category_name']); ?></span>
                <?php endif; ?>
                
                <h3 class="mt-3 text-success product-price">$<?php echo number_format($product['price'], 2); ?></h3>
                
                <div class="mt-4">
                    <h4>Description</h4>
                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>
                
                <div class="mt-4">
                <form action="add_to_cart.php" method="post" class="row g-2 align-items-end">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

                    <!-- Quantity Dropdown -->
                    <div class="col-md-4">
                        <label for="quantity" class="form-label mb-1">Qty</label>
                        <select name="quantity" id="quantity" class="form-select form-select-sm">
                            <?php for ($i = 1; $i <= 10; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <!-- Add to Cart Button -->
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-sm btn-outline-primary w-100 btn-add-to-cart">
                            <i class="bi bi-cart-plus"></i> Add to Cart
                        </button>
                    </div>

                    <!-- Go to Cart Button -->
                    <div class="col-md-4">
                        <a href="cart.php" class="btn btn-sm btn-outline-success w-100 btn-go-to-cart">
                            <i class="bi bi-bag"></i> Go to Cart
                        </a>
                    </div>
                </form>

                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php include 'footer.php'; ?>
