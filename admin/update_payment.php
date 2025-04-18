<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

if (isset($_GET["id"]) && isset($_GET["status"])) {
    $id = $_GET["id"];
    $status = $_GET["status"];
    $conn->query("UPDATE payments SET payment_status='$status' WHERE id='$id'");
}

header("Location: manage_payments.php");
exit;
?>
