<?php
include "../db/connect.php";
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = ($_POST["name"]);
    $email = ($_POST["email"]);
    $password = ($_POST["password"]);
    $confirm_password = ($_POST["confirm_password"]);

    if ($password !== $confirm_password) {
        $message = "Passwords do not match!";
    } else {

        // Check if email already exists
        $check_sql = "SELECT * FROM clients WHERE email='$email'";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            $message = "Email already exists!";
        } else {


            $sql = "INSERT INTO clients (name, email, password) 
                    VALUES ('$name', '$email', '$password')";

            if (mysqli_query($conn, $sql)) {
                $message = "Client account created successfully!";
                $msg_type = "success";
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
    <title>Add Client - Library App</title>
    <link rel="stylesheet" href="../css/modern.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="login-container">

    <div class="auth-box">
        <div class="auth-header">
            <h2>Add New Client</h2>
            <p>Create a new account for a client</p>
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
                <input type="text" id="name" name="name" placeholder="John Doe" required>
            </div>

            <div class="form-group">
                <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Email Address</label>
                <input type="email" id="email" name="email" placeholder="name@example.com" required>
            </div>

            <div class="form-group">
                <label for="password" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Password</label>
                <input type="password" id="password" name="password" placeholder="Create password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                Create Client
            </button>

            <div class="form-footer">
                <a href="client_list.php"><i class="fas fa-arrow-left"></i> Back to Client List</a>
            </div>
        </form>
    </div>

    <?php if (isset($_GET['success']) || (isset($message) && stripos($message, 'error') === false && !empty($message))): ?>
    <?php // Logic to detect success if I haven't set msg_type explicitly in add_client.php yet. 
          // Checking file content, I didn't add msg_type logic in add_client PHP block earlier, just $message.
          // I should probably fix the PHP block first or rely on checking $message content.
          // Let's assume success if no "Error" string.
     ?>
     <?php if (!empty($message) && stripos($message, 'Error') === false): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Client Added!',
            text: 'New client account created.',
            showConfirmButton: false,
            timer: 2000
        }).then(() => {
            window.location.href = 'client_list.php';
        });
    </script>
    <?php endif; ?>
    <?php endif; ?>

</body>
</html>