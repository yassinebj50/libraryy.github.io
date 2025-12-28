<?php
session_start();
include "../db/connect.php";


$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($current_page - 1) * 5;

$sql = "SELECT COUNT(id) AS total FROM clients";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$total = $row['total'];
$total_pages = ceil($total / 5);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selected_client_id'])) {
    $_SESSION['selected_client_id'] = ($_POST['selected_client_id']);
}

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../home.php");
    exit;
}

if (isset($_POST['delete_client_id'])) {
    $id = ($_POST['delete_client_id']);
    $sql_delete = "DELETE FROM clients WHERE id = $id";
    mysqli_query($conn, $sql_delete);
    header("Location: client_list.php");
    exit;
}


$sql = "SELECT * FROM clients ORDER BY id ASC LIMIT 5 OFFSET $offset";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Clients - Library App</title>
    <link rel="stylesheet" href="../css/modern.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <?php include '../includes/header.php'; ?>

    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h2 style="margin-bottom: 0.5rem;">Client Management</h2>
                <p style="color: var(--slate-600);">Oversee registered users and admins.</p>
            </div>
            
            <a href="add_client.php" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Add New Client
            </a>
        </div>

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Active Reservations</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($client = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td>#<?php echo $client['id']; ?></td>
                            <td>
                                <div style="font-weight: 500; color: var(--slate-900);"><?php echo htmlspecialchars($client['name']); ?></div>
                            </td>
                            <td><?php echo htmlspecialchars($client['email']); ?></td>
                            <td>
                                <span class="badge <?php echo ($client['role'] === 'admin') ? 'badge-info' : 'badge-success'; ?>">
                                    <?php echo ucfirst($client['role']); ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="client_reserve.php" style="display:inline;">
                                    <input type="hidden" name="selected_client_id" value="<?php echo $client['id']; ?>">
                                    <button type="submit" class="btn" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; background: var(--slate-100); color: var(--slate-700);">
                                        View History <i class="fas fa-arrow-right" style="margin-left: 5px; font-size: 0.7em;"></i>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <?php if ($client['role'] !== "admin"): ?>
                                    <form method="POST" style="display:inline;" onsubmit="return confirmDeleteForm(event, this);">
                                        <input type="hidden" name="delete_client_id" value="<?php echo $client['id']; ?>">
                                        <button type="submit" class="btn" style="padding: 0.4rem; color: #ef4444; background: transparent;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span style="color: var(--slate-400); font-size: 0.85rem;"><i class="fas fa-lock"></i> Locked</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if (mysqli_num_rows($result) == 0): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--slate-500); padding: 2rem;">No clients found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                <a href="client_list.php?page=<?php echo $i; ?>"
                   class="page-link <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php }; ?>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>