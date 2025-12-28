<?php
if(!session_start()){
    session_start();
}
include "../db/connect.php";
//edit part
if (isset($_POST["edit"])) {
    $_SESSION['edit_book_id'] = $_POST['book_id'];
    header("Location: edit_book.php");
    exit;
}
//delete part
if (isset($_POST["delete"])) {
    $id = $_POST['book_id'];
    $sql = "DELETE FROM books WHERE id = $id";
    mysqli_query($conn, $sql);
    header("Location: list_books.php");
    exit;
}



$sql = "SELECT * FROM books";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Books - Library App</title>
    <link rel="stylesheet" href="../css/modern.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <?php include '../includes/header.php'; ?>

    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h2 style="margin-bottom: 0.5rem;">Library Collection</h2>
                <p style="color: var(--slate-600);">Browse and reserve your favorite books</p>
            </div>
            
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="add_book.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Book
                </a>
            <?php endif; ?>
        </div>

        <div class="books-grid">
            <?php while ($book = mysqli_fetch_assoc($result)) { ?>
                <div class="book-card">
                    <?php if (!empty($book['cover'])): ?>
                        <img src="<?php echo htmlspecialchars($book['cover']); ?>" alt="Cover" class="book-cover">
                    <?php else: ?>
                         <div class="book-cover" style="display: flex; align-items: center; justify-content: center; background: var(--slate-100); color: var(--slate-400);">
                            <i class="fas fa-book" style="font-size: 3rem;"></i>
                         </div>
                    <?php endif; ?>
                    
                    <div class="book-info">
                        <div class="book-title"><?php echo htmlspecialchars($book['title']); ?></div>
                        <div class="book-author">by <?php echo htmlspecialchars($book['author']); ?></div>
                        
                        <div class="book-price">
                            <?php echo htmlspecialchars($book['prix']); ?> DT
                        </div>

                        <div class="book-actions">
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <form method="POST" style="flex:1;">
                                    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                    <button class="btn" name="edit" type="submit" style="width: 100%; background: var(--slate-100); color: var(--slate-600);">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </form>

                                <form method="POST" style="flex:1;" onsubmit="return confirmDeleteForm(event, this);">
                                    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                    <input type="hidden" name="delete" value="1">
                                    <button class="btn" type="submit" style="width: 100%; background: #fee2e2; color: #ef4444;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            <?php else: ?>
                                
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        
        <?php if (mysqli_num_rows($result) == 0): ?>
            <div style="text-align: center; padding: 4rem; color: var(--slate-500);">
                <i class="fas fa-book-open" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                <p>No books found in the library.</p>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>