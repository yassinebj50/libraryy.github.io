<?php
session_start();
include "db/connect.php";

// 1. Check if session already exists
if (isset($_SESSION['client_id'])) {
    if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: home.php");
    }
    exit;
}

// 2. Check for cookie if session is empty
if (isset($_COOKIE["saved_email"])) {
    $saved_email = mysqli_real_escape_string($conn, $_COOKIE['saved_email']);
    $sql = "SELECT * FROM clients WHERE email='$saved_email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Set session variables
        $_SESSION["client_id"] = $user["id"];
        $_SESSION["client_name"] = $user["name"];
        $_SESSION["client_email"] = $user["email"];
        $_SESSION["role"] = $user["role"];

        // Redirect based on role
        if ($user["role"] == 'admin') {
             header("Location: admin/dashboard.php");
        } else {
             header("Location: home.php");
        }
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Library App</title>
    <link rel="stylesheet" href="css/modern.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="justify-content: center; align-items: center; background: linear-gradient(135deg, var(--slate-900) 0%, var(--primary-900) 100%); color: white;">

    <div class="container" style="text-align: center; max-width: 800px;">
        <div style="font-size: 4rem; color: var(--primary-500); margin-bottom: 1.5rem;">
            <i class="fas fa-book-reader"></i>
        </div>
        
        <h1 style="color: white; font-size: 3.5rem; margin-bottom: 1.5rem; letter-spacing: -0.025em;">Library Management<br><span style="color: var(--primary-400);">Simpler than ever.</span></h1>
        
        <p style="font-size: 1.25rem; color: var(--slate-300); margin-bottom: 3rem; line-height: 1.7;">
            Manage books, reservations, and clients with our modern, intuitive platform. Built for efficiency and ease of use.
        </p>

        <div style="display: flex; gap: 1rem; justify-content: center;">
            <a href="auth/login.php" class="btn btn-primary" style="font-size: 1.125rem; padding: 1rem 2rem;">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>
            <a href="auth/signup.php" class="btn" style="background: rgba(255,255,255,0.1); color: white; font-size: 1.125rem; padding: 1rem 2rem; backdrop-filter: blur(10px);">
                <i class="fas fa-user-plus"></i> Sign Up
            </a>
            <a href="apropos.php" class="btn" style="background: transparent; color: var(--slate-300); padding: 1rem 2rem; border: 1px solid var(--slate-600);">
                About Us
            </a>
        </div>
        
        <div style="margin-top: 5rem; color: var(--slate-500); font-size: 0.875rem;">
            &copy; <?php echo date('Y'); ?> Library Mini Project. All rights reserved.
        </div>
    </div>

</body>
</html>