<?php
session_start();
include 'header.php'; 
include "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = $_POST['name'];
    $email   = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Insert into inquiries table (adjust table/field names as needed)
    $sql = "INSERT INTO inquiries (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "Your inquiry has been submitted. Thank you!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contact Us | Multi Vendor Marketplace</title>
  <link rel="stylesheet" href="public/css/stylesheet.css">
  <style>
    /* Importing the Montserrat font for styling */
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap');

    /* Base Styles */
    
  </style>
</head>
<body>
  <div class="contact-container">
    <!-- Page Header -->
    <header class="contact-header">
      <h1>Contact Us</h1>
    </header>

    <!-- Contact Form Section -->
    <section class="contact-form">
      <form method="POST" action="">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="subject" placeholder="Subject" required>
        <textarea name="message" placeholder="Your Message" required></textarea>
        <button type="submit">Submit</button>
      </form>
    </section>
  </div>
</body>
</html>
<?php include 'footer.php'; ?>
