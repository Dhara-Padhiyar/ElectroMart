    <?php include '../views/header.php'; 

    session_start();
    include "../config/db.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
                echo "Invalid password!";
            }
        } else {
            echo "User not found!";
        }
    }
    ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>
    <?php include '../views/footer.php'; ?>