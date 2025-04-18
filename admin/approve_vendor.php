<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET["id"])) {
    header("Location: manage_vendors.php");
    exit;
}

$user_id = intval($_GET["id"]);

// Check if vendor already exists
$check = $conn->prepare("SELECT id FROM vendors WHERE user_id = ?");
$check->bind_param("i", $user_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    // Vendor already exists, update approval
    $update = $conn->prepare("UPDATE vendors SET approved = 1 WHERE user_id = ?");
    $update->bind_param("i", $user_id);
    $update->execute();
} else {
    $default_store = "Store " . $user_id;
    $insert = $conn->prepare("INSERT INTO vendors (user_id, store_name, approved) VALUES (?, ?, 1)");
    $insert->bind_param("is", $user_id, $default_store);
    $insert->execute();
}

header("Location: manage_vendors.php");
exit;
