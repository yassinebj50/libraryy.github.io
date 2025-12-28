<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Library App</title>
    <link rel="stylesheet" href="css/modern.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <?php 
    session_start();
    // Only include header if we have a session, otherwise just show the page simply
    // But header.php handles "if session invalid redirect", so let's check if the user is logged in
    // If not logged in, we can't easily include header.php because it forces redirect.
    // So let's mock a simple header or just use the content if logged out.
    // Actually, users can access this page without login from main.php.
    // Let's rely on standard header include if valid session, else custom header.
    
    if (isset($_SESSION['client_id'])) {
         include 'includes/header.php';
    } else {
    ?>
        <header class="main-header">
            <div class="logo">
                <i class="fas fa-book-reader"></i>
                <span>Library App</span>
            </div>
            <nav class="nav-links">
                <a href="main.php"><i class="fas fa-arrow-left"></i> Back to Main</a>
            </nav>
        </header>
    <?php } ?>

    <div class="container main-content">
        <div class="card" style="padding: 3rem; max-width: 800px; margin: 0 auto;">
            <div style="text-align: center; margin-bottom: 2rem;">
                <div style="width: 80px; height: 80px; background: var(--primary-50); color: var(--primary-600); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1.5rem;">
                    <i class="fas fa-info"></i>
                </div>
                <h1 style="margin-bottom: 0.5rem;">About This Project</h1>
                <p style="color: var(--slate-600);">A Modern Library Management System</p>
            </div>

            <div style="margin-bottom: 2rem;">
                <p style="margin-bottom: 1rem; line-height: 1.8;">
                    Welcome to the Library Mini Project. This application demonstrates a full-stack solution built with <strong>PHP</strong> and <strong>MySQL</strong>. It features detailed client and book management, reservation tracking, and role-based access control.
                </p>
                <div class="dashboard-grid" style="grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-top: 2rem;">
                    <div style="background: var(--slate-50); padding: 1.5rem; border-radius: var(--radius);">
                        <h4 style="margin-bottom: 0.5rem; color: var(--primary-700);"><i class="fas fa-user-shield"></i> Admins</h4>
                        <p style="font-size: 0.9rem; color: var(--slate-600);">Can manage books, clients, and oversee all reservations.</p>
                    </div>
                    <div style="background: var(--slate-50); padding: 1.5rem; border-radius: var(--radius);">
                        <h4 style="margin-bottom: 0.5rem; color: var(--primary-700);"><i class="fas fa-user"></i> Clients</h4>
                        <p style="font-size: 0.9rem; color: var(--slate-600);">Can browse the collection and track their personal borrowing history.</p>
                    </div>
                </div>
            </div>

            <div style="border-top: 1px solid var(--slate-200); padding-top: 2rem; margin-top: 2rem;">
                <h3 style="margin-bottom: 1.5rem;">Project Structure</h3>
                <ul style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <li style="display: flex; align-items: center; gap: 0.5rem; color: var(--slate-700);"><i class="fas fa-folder text-primary"></i> <strong>auth/</strong> Authentication</li>
                    <li style="display: flex; align-items: center; gap: 0.5rem; color: var(--slate-700);"><i class="fas fa-folder text-primary"></i> <strong>book/</strong> Book Management</li>
                    <li style="display: flex; align-items: center; gap: 0.5rem; color: var(--slate-700);"><i class="fas fa-folder text-primary"></i> <strong>client/</strong> Client Features</li>
                    <li style="display: flex; align-items: center; gap: 0.5rem; color: var(--slate-700);"><i class="fas fa-folder text-primary"></i> <strong>css/</strong> Modern Styling</li>
                </ul>
            </div>

            <div style="text-align: center; margin-top: 3rem; background: var(--primary-50); padding: 2rem; border-radius: var(--radius);">
                <h3 style="margin-bottom: 1rem;">Created By</h3>
                <div style="font-size: 1.1rem; font-weight: 600; color: var(--primary-700);">
                   &bull;  Ahmed Yassine Boujnah 
                </div>
            </div>
            
             <?php if (!isset($_SESSION['client_id'])): ?>
                <div style="text-align: center; margin-top: 2rem;">
                    <a href="main.php" class="btn btn-primary">Back to Home</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>