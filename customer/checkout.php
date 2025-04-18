<?php
include '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_to'] = 'checkout.php';
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$cart_items = $conn->query("
    SELECT c.id as cart_id, c.quantity, p.id as product_id, p.name, p.price 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = $user_id
");

if ($cart_items->num_rows == 0) {
    header("Location: cart.php");
    exit();
}

$subtotal = 0;
$cart_array = [];
while($item = $cart_items->fetch_assoc()) {
    $subtotal += $item['price'] * $item['quantity'];
    $cart_array[] = $item;
}

$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = $conn->real_escape_string($_POST['address']);
    $city = $conn->real_escape_string($_POST['city']);
    $state = $conn->real_escape_string($_POST['state']);
    $zip = $conn->real_escape_string($_POST['zip']);
    $paymentMethod = $_POST['paymentMethod'];

    $shipping = 5.00;
    $tax = $subtotal * 0.1;
    $total_price = $subtotal + $shipping + $tax;

    $conn->query("INSERT INTO orders (user_id, total_price, status, created_at) 
                  VALUES ($user_id, $total_price, 'pending', NOW())");
    $order_id = $conn->insert_id;

    foreach ($cart_array as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];
        $price = $item['price'];

        $conn->query("INSERT INTO order_items (order_id, product_id, quantity, price) 
                      VALUES ($order_id, $product_id, $quantity, $price)");

        $conn->query("UPDATE inventory SET stock = stock - $quantity WHERE product_id = $product_id");
    }

    $conn->query("DELETE FROM cart WHERE user_id = $user_id");
    $_SESSION['cart_count'] = 0;

    $transaction_id = 'TRX-' . strtoupper(uniqid());
    $conn->query("INSERT INTO payments (order_id, transaction_id, payment_status) 
                  VALUES ($order_id, '$transaction_id', 'completed')");

    header("Location: thank_you.php?order_id=$order_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - ElectroMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'header.php'; ?>
<div class="checkout-main-content mt-5">
    <h2>Checkout</h2>
    <form method="post" action="" novalidate>
        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white checkout-heading">Shipping Information</div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" pattern="[A-Za-z\s]{1,15}" title="Only letters, max 15 characters" value="<?php echo htmlspecialchars($user['name']); ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">State</label>
                                <input type="text" name="state" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Zip</label>
                                <input type="text" name="zip" class="form-control" required pattern="^[A-Za-z]\d[A-Za-z][ -]?\d[A-Za-z]\d$" title="Enter a valid Canadian postal code (e.g., A1A 1A1)">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white checkout-heading">Payment Method</div>
                    <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="creditCard" value="creditCard" checked required>
                            <label class="form-check-label" for="creditCard">Credit Card</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="paypal" value="paypal">
                            <label class="form-check-label" for="paypal">PayPal</label>
                        </div>

                        <div class="mt-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Card Name</label>
                                    <input type="text" class="form-control" required pattern="[A-Za-z\s]+" title="Only letters allowed">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Card Number</label>
                                    <input type="text" class="form-control" required pattern="\d{16}" maxlength="16" title="Enter 16 digit card number">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Expiry</label>
                                    <input type="month" class="form-control" id="expiry" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">CVV</label>
                                    <input type="text" class="form-control" required pattern="\d{3}" maxlength="3" title="Enter 3 digit CVV">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white checkout-heading">Order Summary</div>
                    <div class="card-body">
                        <h6>Your Items</h6>
                        <?php foreach ($cart_array as $item): ?>
                            <div class="d-flex justify-content-between">
                                <div><?php echo $item['name']; ?> x<?php echo $item['quantity']; ?></div>
                                <div>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
                            </div>
                        <?php endforeach; ?>
                        <hr>
                        <div class="d-flex justify-content-between"><span>Subtotal</span><span>$<?php echo number_format($subtotal, 2); ?></span></div>
                        <div class="d-flex justify-content-between"><span>Shipping</span><span>$5.00</span></div>
                        <div class="d-flex justify-content-between"><span>Tax (10%)</span><span>$<?php echo number_format($subtotal * 0.1, 2); ?></span></div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total</span>
                            <span>$<?php echo number_format($subtotal + 5 + ($subtotal * 0.1), 2); ?></span>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mt-3">Place Order</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?php include 'footer.php'; ?>

<script>
  document.addEventListener('DOMContentLoaded', () => {
      const expiryInput = document.getElementById('expiry');
      const now = new Date();
      const year = now.getFullYear();
      const month = String(now.getMonth() + 1).padStart(2, '0');
      expiryInput.min = `${year}-${month}`;
  });
</script>
</body>
</html>
