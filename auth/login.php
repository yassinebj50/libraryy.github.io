<?php
include "../db/connect.php";
session_start();

$message = "";
$msg_type = "";

// Function to handle successful login
function loginSuccess($user) {
    global $conn;
    $_SESSION["client_id"] = $user["id"];
    $_SESSION["client_name"] = $user["name"];
    $_SESSION["client_email"] = $user["email"];
    $_SESSION["role"] = $user["role"];

    $_SESSION['message'] = "Welcome back, " . $user['name'] . "!";
    $_SESSION['msg_type'] = "success";

    if ($user["role"] == 'admin') {
        header("Location: ../admin/dashboard.php");
    } else {
        header("Location: ../home.php");
    }
    exit;
}

// Auto-login from cookie
if (isset($_COOKIE["saved_email"]) && !isset($_SESSION['client_id'])) {
    $saved_email = mysqli_real_escape_string($conn, $_COOKIE['saved_email']);
    $sql = "SELECT * FROM clients WHERE email='$saved_email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        loginSuccess($user);
    }
}

// Form Login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = $_POST["password"]; 

    // Update cookie
    setcookie("saved_email", $email, time() + (86400 * 30), "/"); // 30 days

    $sql = "SELECT * FROM clients WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Simple password check (should be password_verify in production, keeping as is for now)
        if ($password == $user["password"]) {
            loginSuccess($user);
        } else {
            $message = "Incorrect password.";
            $msg_type = "error";
        }
    } else {
        $message = "Email not found.";
        $msg_type = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Library App</title>
    <link rel="stylesheet" href="../css/modern.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="login-container">

    <div class="auth-box">
        <div class="auth-header">
            <div class="logo" style="justify-content: center; margin-bottom: 1rem;">
                <i class="fas fa-book-reader"></i>
                <span>Library App</span>
            </div>
            <h2>Welcome Back</h2>
            <p>Please log in to your account</p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $msg_type; ?>" style="margin-bottom: 1.5rem;">
                <i class="fas <?php echo ($msg_type == 'success') ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                <span><?php echo $message; ?></span>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Email Address</label>
                <input type="email" id="email" name="email" placeholder="name@example.com" 
                       value="<?php echo isset($_COOKIE['saved_email']) ? htmlspecialchars($_COOKIE['saved_email']) : '' ?>" required>
            </div>

            <div class="form-group">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <label for="password" style="font-weight: 500;">Password</label>

                </div>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                Sign In
            </button>

            <div class="form-footer">
                Don't have an account? <a href="signup.php">Create free account</a>
            </div>
        </form>
    </div>

</body>
</html>