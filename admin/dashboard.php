<?php
include '../includes/header.php';

// Security check: Ensure only admins can access
if ($_SESSION['role'] !== 'admin') {
    echo "<div class='container' style='text-align:center; padding: 5rem 1rem;'>
            <div class='alert alert-error' style='display:inline-flex;'>
                <i class='fas fa-exclamation-triangle'></i>
                <span>Access Denied. You do not have permission to view this page.</span>
            </div>
            <div style='margin-top: 2rem;'>
                 <a href='../home.php' class='btn btn-primary'>Go Home</a>
            </div>
          </div>";
    exit;
}

// Database Connection & Stats Logic
$db_path = '../db/connect.php';
$client_count = 0;
$book_count = 0;
$reservation_count = 0;

if (file_exists($db_path)) {
    include $db_path;
    if (isset($conn)) {
        // Count Books
        $res_books = mysqli_query($conn, "SELECT COUNT(*) as count FROM books");
        if ($res_books && $row = mysqli_fetch_assoc($res_books)) {
            $book_count = $row['count'];
        }

        // Count Clients
        $res_clients = mysqli_query($conn, "SELECT COUNT(*) as count FROM clients");
        if ($res_clients && $row = mysqli_fetch_assoc($res_clients)) {
            $client_count = $row['count'];
        }
        
        // Count Reservations
        $res_reservations = mysqli_query($conn, "SELECT COUNT(*) as count FROM reservations");
        if ($res_reservations && $row = mysqli_fetch_assoc($res_reservations)) {
            $reservation_count = $row['count'];
        }
    }
}
?>

<div class="container">
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: end;">
        <div>
            <h1 style="margin-bottom: 0.5rem;">Admin Dashboard</h1>
            <p style="color: var(--slate-600);">Overview of your library system</p>
        </div>
        <div>
            <span style="color: var(--slate-600); font-size: 0.9rem;">
                <i class="fas fa-clock"></i> <?php echo date('F j, Y'); ?>
            </span>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="dashboard-grid">
        <div class="stat-card">
            <div class="stat-icon bg-primary-soft text-primary">
                <i class="fas fa-book"></i>
            </div>
            <div>
                <h3 style="font-size: 0.875rem; text-transform: uppercase; color: var(--slate-600); margin-bottom: 0.25rem;">Total Books</h3>
                <p style="font-size: 1.5rem; font-weight: 700; color: var(--slate-900);"><?php echo $book_count > 0 ? $book_count : '0'; ?></p> 
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background-color: #f0fdf4; color: #16a34a;">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <h3 style="font-size: 0.875rem; text-transform: uppercase; color: var(--slate-600); margin-bottom: 0.25rem;">Clients</h3>
                <p style="font-size: 1.5rem; font-weight: 700; color: var(--slate-900);"><?php echo $client_count > 0 ? $client_count : '0'; ?></p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background-color: #f3e8ff; color: #9333ea;">
                <i class="fas fa-bookmark"></i>
            </div>
            <div>
                <h3 style="font-size: 0.875rem; text-transform: uppercase; color: var(--slate-600); margin-bottom: 0.25rem;">Reservations</h3>
                <p style="font-size: 1.5rem; font-weight: 700; color: var(--slate-900);"><?php echo $reservation_count > 0 ? $reservation_count : '0'; ?></p>
            </div>
        </div>
    </div>

    <!-- Actions & Recent Activity -->
    <div class="dashboard-grid" style="grid-template-columns: 2fr 1fr; margin-bottom: 4rem;">
        <div class="card">
            <div style="padding: 1.5rem; border-bottom: 1px solid var(--slate-200);">
                <h3>Quick Actions</h3>
            </div>
            <div style="padding: 1.5rem;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <a href="../book/add_book.php" class="btn" style="background: var(--slate-100); color: var(--slate-700); justify-content: start;">
                        <i class="fas fa-plus-circle" style="color: var(--primary-600);"></i> Add New Book
                    </a>
                    <a href="../client/client_list.php" class="btn" style="background: var(--slate-100); color: var(--slate-700); justify-content: start;">
                        <i class="fas fa-user-edit" style="color: var(--primary-600);"></i> Manage Clients
                    </a>
                    <a href="../book/list_books.php" class="btn" style="background: var(--slate-100); color: var(--slate-700); justify-content: start;">
                        <i class="fas fa-list" style="color: var(--primary-600);"></i> View All Books
                    </a>
                </div>
            </div>
        </div>

        <div class="card">
            <div style="padding: 1.5rem; border-bottom: 1px solid var(--slate-200);">
                <h3>System Status</h3>
            </div>
            <div style="padding: 1.5rem;">
                <ul style="display: flex; flex-direction: column; gap: 1rem;">
                    <li style="display: flex; align-items: center; gap: 0.75rem; color: var(--slate-700);">
                        <i class="fas fa-check-circle" style="color: var(--success);"></i> Database Active
                    </li>
                    <li style="display: flex; align-items: center; gap: 0.75rem; color: var(--slate-700);">
                        <i class="fas fa-check-circle" style="color: var(--success);"></i> Server Online
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

</main>
</body>
</html>
