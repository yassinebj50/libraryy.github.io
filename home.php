<?php
// Include the shared header which handles session check and CSS/Fonts
include 'includes/header.php';
?>

<div class="container">
    <div class="card" style="margin-top: 2rem; padding: 3rem; text-align: center; border: none; box-shadow: none; background: transparent;">
        <h1 style="color: var(--primary-600); margin-bottom: 1rem;">Welcome, <?php echo htmlspecialchars($_SESSION['client_name']); ?>!</h1>
        <p style="color: var(--slate-600); font-size: 1.25rem; max-width: 600px; margin: 0 auto 3rem;">
            Explore our vast collection of books, manage your reservations, and check your reading history all in one place.
        </p>

        <div class="dashboard-grid">
            <a href="book/list_books.php" class="stat-card" style="display: block; text-align: left; text-decoration: none;">
                <div style="display: flex; align-items: center; gap: 1.5rem;">
                    <div class="stat-icon bg-primary-soft text-primary">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 1.25rem; margin-bottom: 0.25rem;">Browse Books</h3>
                        <p style="color: var(--slate-600);">Discover new titles</p>
                    </div>
                </div>
            </a>

            <a href="client/client_reserve.php" class="stat-card" style="display: block; text-align: left; text-decoration: none;">
                <div style="display: flex; align-items: center; gap: 1.5rem;">
                    <div class="stat-icon" style="background-color: #f0fdf4; color: #16a34a;">
                        <i class="fas fa-bookmark"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 1.25rem; margin-bottom: 0.25rem;">My Reservations</h3>
                        <p style="color: var(--slate-600);">Check status</p>
                    </div>
                </div>
            </a>
            
             <a href="apropos.php" class="stat-card" style="display: block; text-align: left; text-decoration: none;">
                <div style="display: flex; align-items: center; gap: 1.5rem;">
                    <div class="stat-icon" style="background-color: #eff6ff; color: #2563eb;">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 1.25rem; margin-bottom: 0.25rem;">About Us</h3>
                        <p style="color: var(--slate-600);">Learn more</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

</body>
</html>