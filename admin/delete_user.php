<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $conn->query("DELETE FROM users WHERE id='$id'");
}

header("Location: manage_users.php");
exit;
?>
