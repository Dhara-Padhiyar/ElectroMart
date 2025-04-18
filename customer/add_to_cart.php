<?php
session_start();
include '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $product_id = (int)$_POST['product_id'];
    $quantity = max(1, (int)$_POST['quantity']);

    // Check if the product already exists in the cart
    $checkQuery = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $checkQuery->bind_param("ii", $user_id, $product_id);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows > 0) {
        // Product already in cart, update quantity
        $row = $result->fetch_assoc();
        $newQuantity = $row['quantity'] + $quantity;

        $updateQuery = $conn->prepare("UPDATE cart SET quantity = ?, created_at = NOW() WHERE id = ?");
        $updateQuery->bind_param("ii", $newQuantity, $row['id']);
        $updateQuery->execute();
    } else {
        // Product not in cart, insert new
        $insertQuery = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, created_at) VALUES (?, ?, ?, NOW())");
        $insertQuery->bind_param("iii", $user_id, $product_id, $quantity);
        $insertQuery->execute();
    }

    // Redirect to cart or back to product page
    header("Location: cart.php");
    exit();
} else {
    header("Location: products.php");
    exit();
}
?>
