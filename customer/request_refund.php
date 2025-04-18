<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION["user_id"]) || !isset($_GET["order_id"])) {
    header("Location: orders.php");
    exit;
}

$order_id = $_GET["order_id"];
$user_id = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reason = $_POST["reason"];
    $conn->query("INSERT INTO refunds (order_id, user_id, reason) VALUES ('$order_id', '$user_id', '$reason')");
    echo "Refund request submitted!";
    exit;
}

?>

<h2>Request Refund</h2>
<form method="POST">
    <label>Reason for Refund:</label>
    <textarea name="reason" class="form-control" required></textarea><br>
    <button type="submit" class="btn btn-danger">Submit Refund Request</button>
</form>
<a href="orders.php" class="btn btn-secondary mt-2">Back to Orders</a>
