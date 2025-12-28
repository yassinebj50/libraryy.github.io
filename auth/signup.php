<?php
include "../db/connect.php";
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = ($_POST["name"]);
    $email = ($_POST["email"]);
    $password = ($_POST["password"]);
    $confirm_password = ($_POST["confirm_password"]);

    if ($password != $confirm_password) {
        $message = "Passwords do not match";
    } else {

        // Check if email already exists
        $sql = "SELECT * FROM clients WHERE email='$email'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $message = "Email already exists!";
        } else {


            $sql = "INSERT INTO clients (name, email, password) 
                    VALUES ('$name', '$email', '$password')";

            if (mysqli_query($conn, $sql)) {
                header("Location: login.php");
                exit;
            } else {
                $message = "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Library App</title>
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
            <h2>Create Account</h2>
            <p>Join us to start managing your books</p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo $message; ?></span>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="name" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>
            </div>

            <div class="form-group">
                <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="password" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Password</label>
                <input type="password" id="password" name="password" placeholder="Create a password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                Sign Up
            </button>

            <div class="form-footer">
                Already have an account? <a href="login.php">Log in</a>
            </div>
        </form>
    </div>

</body>
</html>