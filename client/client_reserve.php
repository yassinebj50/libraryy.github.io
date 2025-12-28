<?php
session_start();
include "../db/connect.php";


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selected_client_id'])) {
    $_SESSION['selected_client_id'] = ($_POST['selected_client_id']);
}
$today = date("Y-m-d");


if ($_SESSION['role'] == 'admin' && isset($_SESSION['selected_client_id'])) {
    $client_id = $_SESSION['selected_client_id'];
} elseif ($_SESSION['role'] == 'client') {
    $client_id = $_SESSION['client_id'];
}



$sql = "
SELECT r.*, b.title, b.author, b.prix
FROM reservations r
JOIN books b ON r.book_id = b.id
WHERE r.client_id = $client_id
ORDER BY r.date_start 
";

$result = mysqli_query($conn, $sql);



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservations - Library App</title>
    <link rel="stylesheet" href="../css/modern.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <?php include '../includes/header.php'; ?>

    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h2 style="margin-bottom: 0.5rem;">Reservation History</h2>
                <p style="color: var(--slate-600);">Track your borrowed books and deadlines.</p>
            </div>
            
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="reservation.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> New Reservation
                </a>
            <?php endif; ?>
        </div>

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Book</th>
                        <th>Author</th>
                        <th>Price/Day</th>
                        <th>Duration</th>
                        <th>Start Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { 
                        $is_returned = ($row['date_end'] != "0000-00-00");
                        $days = $is_returned ? intval((strtotime($row['date_end']) - strtotime($row['date_start'])) / 86400) : '-';
                    ?>
                        <tr style="<?php echo $is_returned ? 'opacity: 0.7;' : ''; ?>">
                            <td style="font-weight: 500; color: var(--slate-900);">
                                <i class="fas fa-book" style="color: var(--primary-500); margin-right: 8px;"></i>
                                <?php echo htmlspecialchars($row['title']); ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['author']); ?></td>
                            <td><?php echo htmlspecialchars($row['prix']); ?> DT</td>
                            <td><?php echo $days; ?> days</td>
                            <td><?php echo date('M d, Y', strtotime($row['date_start'])); ?></td>
                            <td>
                                <?php echo $is_returned ? date('M d, Y', strtotime($row['date_end'])) : '<span style="color: var(--slate-400);">Ongoing</span>'; ?>
                            </td>
                            <td>
                                <?php if (!$is_returned): ?>
                                    <span class="badge badge-warning">Active</span>
                                    <?php if ($_SESSION['role'] === 'admin'): ?>
                                         <form action="finish_reservation.php" method="post" style="display:inline; margin-left: 10px;" onsubmit="return confirmReturn(event, this);">
                                            <input type="hidden" name="reservation_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" class="btn" style="padding: 0.5rem 1rem; font-size: 0.9rem; font-weight: 600; background: var(--success); color: white; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                                <i class="fas fa-undo"></i> Return Book
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge badge-success">Returned</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if (mysqli_num_rows($result) == 0): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--slate-500); padding: 2rem;">No reservations found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div style="margin-top: 1.5rem;">
             <a href="../home.php" class="btn" style="background: var(--slate-200); color: var(--slate-700);">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
        </div>
    </div>

</body>
</html>