<?php
session_start();

// Destroy the session
session_unset();
session_destroy();

// Redirect to the login page
header("Location: auth.php");  // Redirect to your combined Login/Registration page
exit;
?>
