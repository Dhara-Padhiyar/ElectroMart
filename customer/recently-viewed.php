<?php include '../config/db.php'; 
session_start();

$user_id = $_SESSION['user_id'] ?? 0;
$recently_viewed = [];

// Fetch last 5 recently viewed products
if ($user_id > 0) {
    $sql = "
        SELECT p.id, p.name, p.price, p.image, rv.viewed_at, c.name AS category_name
        FROM recently_viewed rv
        JOIN products p ON rv.product_id = p.id
        LEFT JOIN categories c ON p.category = c.id
        WHERE rv.id = ?
        ORDER BY rv.viewed_at DESC
        LIMIT 5
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $recently_viewed[] = $row;
    }
    $stmt->close();
}

function time_elapsed_string($datetime) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = [
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    ];

    foreach ($string as $k => $v) {
        if ($diff->$k) {
            return $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '') . ' ago';
        }
    }
    return 'just now';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recently Viewed</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/stylesheet.css">
    <style>
    .recently-viewed-container {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        margin: 20px;
        display: flex;
        align-items: center;
        gap: 5px !important;
    }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="dashboard-container">
        <?php include 'dashboard-sidebar.php'; ?>
        
        <main class="profile-main-content">
            <h1><i class="fas fa-clock"></i> Recently Viewed</h1>
            
            <div class="recently-viewed-container">
                <?php if (!empty($recently_viewed)): ?>
                    <?php foreach ($recently_viewed as $product): ?>
                        <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <?php if (!empty($product['image']) && file_exists("../images/" . $product['image'])): ?>
                            <img src="../images/<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <?php else: ?>
                            <div class="bg-secondary text-white text-center p-5 rounded" style="height: 250px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-image" style="font-size: 3rem;"></i>
                            </div>
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text text-success">₹<?php echo number_format($product['price'], 2); ?></p>
                            <?php if (!empty($product['category_name'])): ?>
                                <span class="badge bg-primary mb-2"><?php echo htmlspecialchars($product['category_name']); ?></span>
                            <?php endif; ?>
                            <p class="text-muted small mb-3">Viewed <?php echo time_elapsed_string($product['viewed_at']); ?></p>
                            <a href="product_details.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-primary mt-auto">
                                <i class="bi bi-eye"></i> View Again
                            </a>
                        </div>
                    </div>
                </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-recently-viewed">
                        <i class="fas fa-clock" style="font-size: 50px; margin-bottom: 15px;"></i>
                        <h3>No Recently Viewed Items</h3>
                        <p>Products you view will appear here for easy access.</p>
                        <a href="<?php echo $base_path; ?>product.php" class="btn btn-primary mt-3">
                            <i class="fas fa-shopping-bag"></i> Start Shopping
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
    
    <?php include 'footer.php'; ?>
    
    <script>
        // Add to cart functionality
        document.querySelectorAll('.recent-product-actions button').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                // AJAX call to add to cart
                fetch('add-to-cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${productId}&quantity=1`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Product added to cart!');
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
            });
        });
    </script>
</body>
</html>