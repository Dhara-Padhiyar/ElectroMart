<?php
session_start();
include "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Insert into Users table
    $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";

    if ($conn->query($sql) === TRUE) {
        $user_id = $conn->insert_id;

        // If role is vendor, create an entry in vendors table
        if ($role == "vendor") {
            $store_name = $_POST['store_name'];
            $conn->query("INSERT INTO vendors (user_id, store_name, approved) VALUES ('$user_id', '$store_name', FALSE)");
        }

        echo "Registration successful! <a href='login.php'>Login</a>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<form method="POST">
    <input type="text" name="name" placeholder="Full Name" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <select name="role" onchange="toggleVendorField()">
        <option value="customer">Customer</option>
        <option value="vendor">Vendor</option>
    </select><br>
    <div id="vendorField" style="display: none;">
        <input type="text" name="store_name" placeholder="Store Name">
    </div>
    <button type="submit">Register</button>
</form>

<script>
function toggleVendorField() {
    let role = document.querySelector("select[name='role']").value;
    document.getElementById("vendorField").style.display = (role === "vendor") ? "block" : "none";
}
</script>
