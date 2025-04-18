<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $conn->query("UPDATE vendors SET approved = TRUE WHERE id='$id'");
}

header("Location: manage_vendors.php");
exit;
?>
