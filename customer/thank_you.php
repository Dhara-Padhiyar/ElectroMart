<?php
$order_id = isset($_GET['order_id']) ? htmlspecialchars($_GET['order_id']) : 'N/A';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Thank You - ElectroMart</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<style>
      .thank-you-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 15px;
      margin: 50px auto;
      text-align: center;
    }
    .thank-you-box {
      background-color: #0455ae;
      border-radius: 12px;
      padding: 40px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.07);
      text-align: center;
      animation: fadeIn 0.8s ease-in-out;
    }
    .thank-you-box h1 {
      font-size: 2.5rem;
      color: #fffd02;
      margin-bottom: 20px;
    }
    .thank-you-box p {
      font-size: 1.1rem;
      color: #fff;
      margin-bottom: 10px;
    }
    .order-id {
      font-weight: bold;
      color: #fffd02;
      font-size: 1.2rem;
    }
    .btn-home {
      margin-top: 25px;
      padding: 10px 25px;
      font-size: 1rem;
      border-radius: 8px;
      background-color: #fffd02;
      color: #333;
    }
    .btn-home:hover {
      background-color: #fff;
      color: #0455ae;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }
</style>
<body>
  <div class="thank-you-container">
    <div class="thank-you-box col-md-8 col-lg-6">
      <h1><i class="fas fa-check-circle me-2"></i>Thank You!</h1>
      <p>Your order has been placed successfully.</p>
      <p>Your Order Number is: <span class="order-id">#<?php echo $order_id; ?></span></p>
      <p>We’ve sent a confirmation email with the details of your purchase.</p>
      <a href="dashboard.php" class="btn btn-primary btn-home"><i class="fas fa-store me-1"></i> Continue Shopping</a>
    </div>
  </div>
  </body>
</html>
