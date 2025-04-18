    <?php
    include "./config/db.php";
    session_start();

    // Search functionality
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $category_filter = isset($_GET['category']) ? (int)$_GET['category'] : 0;

    // Pagination
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $per_page = 9;
    $offset = ($page - 1) * $per_page;

    // Base query
    $query = "SELECT p.*, c.name as category_name FROM products p 
            LEFT JOIN categories c ON p.category = c.id 
            WHERE 1=1";

    // Add search filter if provided
    if (!empty($search)) {
        $query .= " AND (p.name LIKE '%$search%' OR p.description LIKE '%$search%')";
    }

    // Add category filter if provided
    if ($category_filter > 0) {
        $query .= " AND p.category = $category_filter";
    }

    // Get total products
    $total_result = $conn->query(str_replace('p.*, c.name as category_name', 'COUNT(*) as total', $query));
    $total_row = $total_result->fetch_assoc();
    $total_products = $total_row['total'];
    $total_pages = ceil($total_products / $per_page);

    // Add pagination to query
    $query .= " LIMIT $per_page OFFSET $offset";

    // Get products for current page
    $result = $conn->query($query);

    // Get categories for filter dropdown
    $categories = $conn->query("SELECT * FROM categories");
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Products - ElectroMart</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="public/css/stylesheet.css" rel="stylesheet">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
        <style>
            .profile-main-content {
                max-width: 1140px;
                margin top: 60px !important;
                margin bottom: 60px !important;
                padding: 30px;
                width: 100%;
            }

        </style>
    </head>
    <body>
    <?php include './views/header.php';  ?>
            <div class="profile-main-content" style="margin-left: auto !important; margin-right: auto !important;">
            <h2>All Products</h2>
            <div class="without-sidebar-container">
            <!-- Search and Filter Section -->
            <div class="search-container mb-4">
                <form method="get" class="row g-3">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="category" class="form-select">
                            <option value="0">All Categories</option>
                            <?php while($cat = $categories->fetch_assoc()): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo $category_filter == $cat['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100 btnsbmt"><i class="bi bi-search"></i> Search</button>
                    </div>
                </form>
            </div>
            
            <?php if ($result->num_rows == 0): ?>
                <div class="alert alert-info">No products found matching your criteria.</div>
            <?php else: ?>
                <div class="row">
                    <?php while($product = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <?php if (!empty($product['image']) && file_exists("images/" . $product['image'])): ?>
                                <img src="images/<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <?php else: ?>
                                <div class="bg-secondary text-white text-center p-4" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-image" style="font-size: 3rem;"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-body d-flex flex-column">
                                <div>
                                    <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                    <?php if (!empty($product['category_name'])): ?>
                                        <span class="badge bg-primary category-badge"><?php echo htmlspecialchars($product['category_name']); ?></span>
                                    <?php endif; ?>
                                    <p class="card-text mt-2">
                                        <strong>$<?php echo number_format($product['price'], 2); ?></strong><br>
                                        
                                    </p>
                                </div>
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between">
                                        <a href="./auth/auth.php ?>" class="btn btn-outline-primary">Details</a>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
                
                <!-- Pagination -->
                <nav aria-label="Page navigation" class="Product">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo $category_filter; ?>">Previous</a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo $category_filter; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo $category_filter; ?>">Next</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php include './views/footer.php'; ?>