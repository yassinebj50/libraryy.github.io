<?php
session_start();
include "../db/connect.php";

if ($_SESSION['role'] != 'admin') {
    header("Location: ../home.php");
    exit;
}


$client_id = $_SESSION['selected_client_id'];


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_id'])) {
    $book_id = ($_POST['book_id']);
    $date_start = date("Y-m-d");

    $sql_insert = "INSERT INTO reservations (client_id, book_id, date_start, date_end) 
                   VALUES ($client_id, $book_id, '$date_start', '0000-00-00')";
    
    if (mysqli_query($conn, $sql_insert)) {
        $message = "Reservation added successfully! <a href='client_reserve.php'>View History</a>";
        $msg_type = "success";
    } else {
        $message = "Error: " . mysqli_error($conn);
        $msg_type = "error";
    }
}


$sql_books = "SELECT * FROM books ";
$result_books = mysqli_query($conn, $sql_books);


$sql_client = "SELECT * FROM clients where id=$client_id";
$result_client = mysqli_query($conn, $sql_client);
$result_client = mysqli_fetch_assoc($result_client);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Reservation - Library App</title>
    <link rel="stylesheet" href="../css/modern.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="login-container">

    <div class="auth-box">
        <div class="auth-header">
            <h2>New Reservation</h2>
            <p>For client: <strong><?php echo htmlspecialchars($result_client["name"]); ?></strong></p>
        </div>
        
        <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo $msg_type; ?>">
                <i class="fas <?php echo ($msg_type == 'success') ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                <span><?php echo $message; ?></span>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="book_id" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Select Book</label>
                <select name="book_id" id="book_id" required>
                    <option value="">-- Choose a book --</option>
                    <?php while ($book = mysqli_fetch_assoc($result_books)) { ?>
                        <option value="<?php echo $book['id']; ?>">
                            <?php echo htmlspecialchars($book['title']) . " by " . htmlspecialchars($book['author']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                Confirm Reservation
            </button>

            <div class="form-footer">
                <a href="client_reserve.php"><i class="fas fa-arrow-left"></i> Back to History</a>
            </div>
        </form>
    </div>

    <?php if (isset($msg_type) && $msg_type == 'success'): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Reserved!',
            text: 'Reservation created successfully.',
            showConfirmButton: false,
            timer: 2000
        }).then(() => {
            window.location.href = 'client_reserve.php';
        });
    </script>
    <?php endif; ?>

</body>
</html>