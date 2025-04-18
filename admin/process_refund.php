<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin" || !isset($_GET["id"]) || !isset($_GET["status"])) {
    header("Location: manage_refunds.php");
    exit;
}

$id = $_GET["id"];
$status = $_GET["status"];
$conn->query("UPDATE refunds SET status='$status', processed_at=NOW() WHERE id='$id'");

header("Location: manage_refunds.php");
exit;
?>
