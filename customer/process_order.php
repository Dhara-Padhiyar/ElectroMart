<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$transaction_id = $_GET["transaction_id"];

// Insert Order
$conn->query("INSERT INTO orders (user_id, total_price, status) VALUES ('$user_id', (SELECT SUM(products.price * cart.quantity) FROM cart JOIN products ON cart.product_id = products.id WHERE cart.user_id='$user_id'), 'pending')");
$order_id = $conn->insert_id;

// Insert Payment Record
$conn->query("INSERT INTO payments (order_id, transaction_id, payment_status) VALUES ('$order_id', '$transaction_id', 'completed')");

// Clear Cart
$conn->query("DELETE FROM cart WHERE user_id='$user_id'");

echo "Order Placed Successfully!";
header("Location: order_success.php");
exit;
?>
