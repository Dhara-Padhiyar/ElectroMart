<?php 
include '../views/header.php'; 
session_start();
include "../config/db.php";

// Handle Login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION["user_id"] = $user['id'];
            $_SESSION["role"] = $user['role'];

            // Redirect based on role
            if ($user['role'] == "admin") {
                header("Location: ../admin/dashboard.php");
            } elseif ($user['role'] == "vendor") {
                header("Location: ../vendor/dashboard.php");
            } else {
                header("Location: ../customer/dashboard.php");
            }
            exit;
        } else {
            $login_error = "Invalid password!";
        }
    } else {
        $login_error = "User not found!";
    }
}

// Handle Registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Insert into Users table
    $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";

    if ($conn->query($sql) === TRUE) {
        $user_id = $conn->insert_id;

        // If role is vendor, add to vendors table
        if ($role == "vendor") {
            $store_name = $_POST['store_name'];
            $conn->query("INSERT INTO vendors (user_id, store_name, approved) VALUES ('$user_id', '$store_name', FALSE)");
        }

        $register_success = "Registration successful! <a href='#' onclick='toggleForm(\"login\")'>Login</a>";
    } else {
        $register_error = "Error: " . $conn->error;
    }
}
?>

<div class="container mt-5 login-container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            
            <!-- Alerts -->
            <?php if (isset($login_error)) echo "<div class='alert alert-danger'>$login_error</div>"; ?>
            <?php if (isset($register_error)) echo "<div class='alert alert-danger'>$register_error</div>"; ?>
            <?php if (isset($register_success)) echo "<div class='alert alert-success'>$register_success</div>"; ?>

            <!-- Toggle Forms -->
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header text-center bg-primary text-white fw-bold welcome-text">
                    Welcome to ElectroMart
                </div>
                <div class="card-body">
                    <div id="login-form" style="display: block;">
                        <h3 class="text-center mb-3">Login</h3>
                        <form method="POST">
                            <div class="mb-3">
                                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                            </div>
                            <button type="submit" name="login" class="btn btn-primary w-100 btnsubmit">Login</button>
                        </form>
                        <p class="mt-3 text-center">
                            Don't have an account? <a class="register-link" href="#" onclick="toggleForm('register')">Register here</a>
                        </p>
                    </div>

                    <div id="register-form" style="display: none;">
                        <h3 class="text-center mb-3">Register</h3>
                        <form method="POST">
                            <div class="mb-3">
                                <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
                            </div>
                            <div class="mb-3">
                                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" name="password" class="form-control" placeholder="Create a password" required>
                            </div>
                            <div class="mb-3">
                                <select name="role" onchange="toggleVendorField()" class="form-control">
                                    <option value="customer">Customer</option>
                                    <option value="vendor">Vendor</option>
                                </select>
                            </div>

                            <div id="vendorField" class="mb-3" style="display: none;">
                                <label>Store Name</label>
                                <input type="text" name="store_name" class="form-control" placeholder="Your store name">
                            </div>

                            <button type="submit" name="register" class="btn btn-success w-100 btnsubmit">Register</button>
                        </form>
                        <p class="mt-3 text-center">
                            Already have an account? <a class="register-link" href="#" onclick="toggleForm('login')">Login here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleForm(formType) {
    document.getElementById('login-form').style.display = (formType === 'login') ? 'block' : 'none';
    document.getElementById('register-form').style.display = (formType === 'register') ? 'block' : 'none';
}

function toggleVendorField() {
    let role = document.querySelector("select[name='role']").value;
    document.getElementById("vendorField").style.display = (role === "vendor") ? "block" : "none";
}
</script>

<?php include '../views/footer.php'; ?>
