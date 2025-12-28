<?php
if (session_id() == "") {
    session_start();
}

// Basic auth check for completeness, though specific pages should enforce it too
if (!isset($_SESSION['client_id'])) {
    // Determine path to login based on current location
    $current_folder_check = basename(dirname($_SERVER['SCRIPT_NAME']));
    $sub_dirs_check = array('admin', 'auth', 'book', 'client');
    $path_to_root = in_array($current_folder_check, $sub_dirs_check) ? '../' : './';
    header("Location: " . $path_to_root . "auth/login.php");
    exit;
}

$role = isset($_SESSION['role']) && $_SESSION['role'] == 'admin' ? 'admin' : 'client';
$name = isset($_SESSION['client_name']) ? $_SESSION['client_name'] : 'User';

// Determine relative paths
$current_folder = basename(dirname($_SERVER['SCRIPT_NAME']));
$sub_dirs = array('admin', 'auth', 'book', 'client');
$base_url = in_array($current_folder, $sub_dirs) ? '../' : './';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management</title>
    <!-- Modern CSS for beautiful UI -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/modern.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(event, url) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            })
        }
        function confirmDeleteForm(event, form) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        }
        function confirmReturn(event, form) {
            event.preventDefault();
            Swal.fire({
                title: 'Return Book?',
                text: "Mark this reservation as returned?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, return it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        }
        function confirmLogout(event, url) {
            event.preventDefault();
            Swal.fire({
                title: 'Sign Out',
                text: "Are you sure you want to log out?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#64748b',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Log Out',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            })
        }
    </script>
</head>
<body>

<header class="main-header">
    <div class="logo">
        <i class="fas fa-book-reader"></i>
        <span>Library App</span>
    </div>
    
    <nav class="nav-links">
        <?php if ($role === 'admin'): ?>
            <a href="<?php echo $base_url; ?>admin/dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="<?php echo $base_url; ?>book/list_books.php">
                <i class="fas fa-book"></i> Books
            </a>
            <a href="<?php echo $base_url; ?>client/client_list.php">
                <i class="fas fa-users"></i> Clients
            </a>
        <?php else: ?>
            <a href="<?php echo $base_url; ?>home.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'home.php' ? 'active' : ''; ?>">
                <i class="fas fa-home"></i> Home
            </a>
            <a href="<?php echo $base_url; ?>book/list_books.php">
                <i class="fas fa-book"></i> Browse Books
            </a>
            <a href="<?php echo $base_url; ?>client/client_reserve.php">
                <i class="fas fa-bookmark"></i> My Reservations
            </a>
        <?php endif; ?>
        
         <a href="<?php echo $base_url; ?>apropos.php">
            <i class="fas fa-info-circle"></i> About
        </a>
    </nav>

    <div class="user-menu">
        <span class="user-name">Welcome, <?php echo htmlspecialchars($name); ?></span>
        <a href="<?php echo $base_url; ?>auth/logout.php" class="btn-logout" onclick="confirmLogout(event, this.href);">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</header>

<!-- Flash Messages -->
<?php if (isset($_SESSION['message'])): ?>
    <div class="alert-container">
        <?php $mType = isset($_SESSION['msg_type']) ? $_SESSION['msg_type'] : 'info'; ?>
        <div class="alert alert-<?php echo $mType; ?>">
            <i class="fas <?php echo ($mType == 'success') ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
            <span><?php echo $_SESSION['message']; ?></span>
        </div>
    </div>
    <?php 
    // Clear message after showing
    unset($_SESSION['message']);
    unset($_SESSION['msg_type']);
    ?>
<?php endif; ?>

<main class="main-content">
