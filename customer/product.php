<?php
session_start();
include '../config/db.php';

// ✅ Handle Wishlist
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['wishlist_product_id'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $product_id = (int)$_POST['wishlist_product_id'];
    $customer_id = (int)$_SESSION['user_id'];

    $check = $conn->prepare("SELECT * FROM wiselist WHERE productid = ? AND customerid = ?");
    $check->bind_param("ii", $product_id, $customer_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows === 0) {
        $stmt = $conn->prepare("INSERT INTO wiselist (productid, customerid, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $product_id, $customer_id);
        $stmt->execute();
        $_SESSION['wishlist_msg'] = "Product added to your wishlist!";
    } else {
        $_SESSION['wishlist_msg'] = "Product already in wishlist.";
    }

    header("Location: " . $_SERVER["PHP_SELF"]);
    exit;
}

// ✅ Handle Compare Add
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['compare_product_id'])) {
    $compare_id = (int)$_POST['compare_product_id'];
    if (!isset($_SESSION['compare'])) {
        $_SESSION['compare'] = [];
    }
    if (!in_array($compare_id, $_SESSION['compare'])) {
        $_SESSION['compare'][] = $compare_id;
    }
    header("Location: " . $_SERVER["PHP_SELF"]);
    exit;
}

// ✅ Search & Filter Logic
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category_filter = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Fetch categories for filter dropdown
$categories = $conn->query("SELECT * FROM categories");

// ✅ Fetch Products with Search/Filter
$products = [];
$sql = "SELECT p.*, c.name as category_name FROM products p 
        LEFT JOIN categories c ON p.category = c.id 
        WHERE 1=1";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND p.name LIKE '%$search%'";
}

if ($category_filter > 0) {
    $sql .= " AND p.category = $category_filter";
}

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Products</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="profile-container">
    <?php include 'dashboard-sidebar.php'; ?>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">All Products</h2>
            <a href="compare.php" class="btn btn-outline-primary">
                <i class="bi bi-columns-gap"></i> View Comparison
            </a>
        </div>

        <!-- ✅ Search & Filter Form -->
        <div class="search-container mb-4">
            <form method="get" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="0">All Categories</option>
                        <?php while ($cat = $categories->fetch_assoc()): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo $category_filter == $cat['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100 btnsbmt">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </form>
        </div>

        <?php if (isset($_SESSION['wishlist_msg'])): ?>
            <div class="alert alert-info"><?php echo $_SESSION['wishlist_msg']; unset($_SESSION['wishlist_msg']); ?></div>
        <?php endif; ?>

        <div class="row">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm">
                            <?php if (!empty($product['image'])): ?>
                                <img src="../images/<?php echo $product['image']; ?>" class="card-img-top" alt="Product Image">
                            <?php else: ?>
                                <img src="placeholder.jpg" class="card-img-top" alt="No Image">
                            <?php endif; ?>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?php echo $product['name']; ?></h5>
                                <p class="card-text mb-1">Price: ₹<?php echo $product['price']; ?></p>
                                <?php if (!empty($product['category_name'])): ?>
                                    <span class="badge text-white bg-primary px-2 py-1 rounded-0"><?php echo htmlspecialchars($product['category_name']); ?></span>
                                <?php endif; ?>

                                <div class="d-flex justify-content-between mt-3">
                                    <a href="product_details.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-primary">Details</a>
                                    <form method="post">
                                        <input type="hidden" name="wishlist_product_id" value="<?php echo $product['id']; ?>">
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="bi bi-heart"></i> Wishlist
                                        </button>
                                    </form>
                                </div>

                                <form method="post" class="mt-2">
                                    <input type="hidden" name="compare_product_id" value="<?php echo $product['id']; ?>">
                                    <button type="submit" class="btn btn-outline-secondary w-100">
                                        <i class="bi bi-arrow-left-right"></i> Compare
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">No products found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
