<?php
include '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = $_GET['order_id'] ?? null;
$product_id = null;
$editing_review_id = $_GET['edit'] ?? null;

// Step 1: Get product_id from order_items using order_id
if ($order_id && is_numeric($order_id)) {
    $stmt = $conn->prepare("SELECT product_id FROM order_items WHERE order_id = ? LIMIT 1");
    if (!$stmt) {
        die("Prepare failed for order_items: " . $conn->error);
    }
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->bind_result($fetched_product_id);
    if ($stmt->fetch()) {
        $product_id = $fetched_product_id;
    }
    $stmt->close();
}

// Handle delete request
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = (int) $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $delete_id, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: reviews.php?deleted=1");
    exit();
}

// Handle edit submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_review_id'], $_POST['new_rating'], $_POST['new_review_text'])) {
    $review_id = (int) $_POST['edit_review_id'];
    $new_rating = (int) $_POST['new_rating'];
    $new_text = trim($_POST['new_review_text']);

    $stmt = $conn->prepare("UPDATE reviews SET rating = ?, review_text = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("isii", $new_rating, $new_text, $review_id, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: reviews.php?updated=1");
    exit();
}

// Handle new review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'], $_POST['review_text'], $_POST['product_id'])) {
    $rating = (int) $_POST['rating'];
    $review_text = trim($_POST['review_text']);
    $product_id = (int) $_POST['product_id'];

    $stmt = $conn->prepare("INSERT INTO reviews (user_id, product_id, rating, review_text, created_at) VALUES (?, ?, ?, ?, NOW())");
    if (!$stmt) {
        die("Prepare failed for review insert: " . $conn->error);
    }
    $stmt->bind_param("iiis", $user_id, $product_id, $rating, $review_text);
    if (!$stmt->execute()) {
        die("Review insert failed: " . $stmt->error);
    }
    $stmt->close();

    header("Location: reviews.php?review=success");
    exit();
}

// Fetch user reviews
$reviews = [];
$query = "SELECT r.*, p.name AS product_name, p.image AS product_image 
          FROM reviews r 
          JOIN products p ON r.product_id = p.id 
          WHERE r.user_id = ? 
          ORDER BY r.created_at DESC";

$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Prepare failed for review fetch: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $reviews[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Reviews</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/stylesheet.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="dashboard-container">
    <?php include 'dashboard-sidebar.php'; ?>

    <main class="profile-main-content">
        <h1><i class="fas fa-star"></i> My Reviews</h1>

        <?php if (isset($_GET['review']) && $_GET['review'] === 'success'): ?>
            <div class="alert alert-success">✅ Review submitted successfully!</div>
        <?php elseif (isset($_GET['deleted'])): ?>
            <div class="alert alert-success">🗑️ Review deleted successfully!</div>
        <?php elseif (isset($_GET['updated'])): ?>
            <div class="alert alert-success">✏️ Review updated successfully!</div>
        <?php endif; ?>

        <?php if ($product_id): ?>
        <div class="review-box" style="background: #fff; border: 1px solid #ddd; border-radius: 10px; padding: 30px; max-width: 600px; margin: 30px auto;">
            <h2 style="text-align: center; color: #2c3e50;">Leave a Review</h2>
            <form method="POST">
                <input type="hidden" name="product_id" value="<?= $product_id ?>">
                <label for="rating"><strong>Rating (1-5):</strong></label>
                <select name="rating" id="rating" required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #ccc; margin-top: 5px;">
                    <option value="">Select</option>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?> ★</option>
                    <?php endfor; ?>
                </select>

                <label for="review_text"><strong>Your Review:</strong></label>
                <textarea name="review_text" id="review_text" rows="5" placeholder="Share your thoughts..." required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #ccc; margin-top: 5px;"></textarea>
                <button type="submit" class="btnreview">Submit Review</button>
            </form>
        </div>
        <?php endif; ?>

        <div class="reviews-container">
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $review): ?>
                <div class="review-item">
                    <img src="../images/<?php echo $review['product_image']; ?>" alt="<?php echo $review['product_name']; ?>" class="review-product-img">
                    <div class="review-content">
                        <div class="review-header">
                            <h3 class="review-product-name"><?php echo $review['product_name']; ?></h3>
                            <span class="review-date"><?php echo date('M d, Y', strtotime($review['created_at'])); ?></span>
                        </div>

                        <div class="review-rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star<?php echo $i <= $review['rating'] ? '' : '-empty'; ?>"></i>
                            <?php endfor; ?>
                        </div>

                        <?php if ($editing_review_id == $review['id']): ?>
                            <form method="POST" style="margin-top: 10px;">
                                <input type="hidden" name="edit_review_id" value="<?= $review['id'] ?>">
                                <label><strong>Edit Rating:</strong></label>
                                <select name="new_rating" required style="width: 100%; padding: 10px; margin-top: 5px;">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <option value="<?= $i ?>" <?= ($i == $review['rating']) ? 'selected' : '' ?>><?= $i ?> ★</option>
                                    <?php endfor; ?>
                                </select>
                                <label><strong>Edit Review:</strong></label>
                                <textarea name="new_review_text" rows="4" required style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #e9e9e9;"><?= htmlspecialchars($review['review_text']) ?></textarea>
                                <button type="submit" class="btnreview" style="margin-top: 10px; padding: 10px 25px; background-color: #0455ae; color: #fff; border: none; border-radius: 6px;">Update Review</button>
                            </form>
                        <?php else: ?>
                            <p class="review-text"><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
                            <div class="review-actions">
                                <a href="reviews.php?edit=<?= $review['id'] ?>"><i class="fas fa-edit"></i> Edit Review</a>
                                <a href="reviews.php?delete=<?= $review['id'] ?>" onclick="return confirm('Are you sure you want to delete this review?');"><i class="fas fa-trash"></i> Delete Review</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-reviews">
                    <i class="fas fa-star" style="font-size: 50px; margin-bottom: 15px;"></i>
                    <h3>No Reviews Yet</h3>
                    <p>You haven't reviewed any products. Your reviews help other shoppers!</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
