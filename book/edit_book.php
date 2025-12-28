<?php
session_start();
include "../db/connect.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../home.php");
    exit;
}

// Check if session has edit_book_id he his not go to list
if (!isset($_SESSION['edit_book_id'])) {
    header("Location: list_books.php");
    exit;
}

$book_id = $_SESSION['edit_book_id'];



$sql = "SELECT * FROM books WHERE id = $book_id";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) < 1) {
    die("Book not found.");
}
$book = mysqli_fetch_assoc($result);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = ($_POST['title']);
    $author = ($_POST['author']);
    $prix = ($_POST['prix']);



    $cover = $book['cover']; // Keep existing by default
    
    // Check cover type selection
    if (isset($_POST['cover_type']) && $_POST['cover_type'] === 'upload') {
        if (isset($_FILES['cover_file']) && $_FILES['cover_file']['error'] === 0) {
            $upload_dir = "../uploads/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_ext = pathinfo($_FILES['cover_file']['name'], PATHINFO_EXTENSION);
            $file_name = uniqid('book_', true) . '.' . $file_ext;
            $target_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['cover_file']['tmp_name'], $target_path)) {
                $cover = $target_path;
            }
        }
    } elseif (isset($_POST['cover_type']) && $_POST['cover_type'] === 'url') {
        $cover = $_POST['cover_url'];
    }

    $sql_update = "UPDATE books SET title='$title', author='$author', prix='$prix', cover='$cover' WHERE id=$book_id";
    if (mysqli_query($conn, $sql_update)) {
        // Fetch updated data
        $sql = "SELECT * FROM books WHERE id = $book_id";
        $result = mysqli_query($conn, $sql);
        $book = mysqli_fetch_assoc($result);
        
        $message = "Book updated successfully! <a href='list_books.php'>View List</a>";
        $msg_type = "success";
    } else {
        $message = "Error updating book: " . mysqli_error($conn);
        $msg_type = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book - Library App</title>
    <link rel="stylesheet" href="../css/modern.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="login-container">

    <div class="auth-box">
        <div class="auth-header">
            <h2>Edit Book</h2>
            <p>Update book information</p>
        </div>

        <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo $msg_type; ?>">
                <i class="fas <?php echo ($msg_type == 'success') ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                <span><?php echo $message; ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Title</label>
                <input type="text" id="title" name="title" placeholder="Book Title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
            </div>

            <div class="form-group">
                <label for="author" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Author</label>
                <input type="text" id="author" name="author" placeholder="Author Name" value="<?php echo htmlspecialchars($book['author']); ?>" required>
            </div>

            <div class="form-group">
                <label for="prix" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Price (DT)</label>
                <input type="text" id="prix" name="prix" placeholder="Price" value="<?php echo htmlspecialchars($book['prix']); ?>" required>
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Cover Image</label>
                
                <!-- Current Image Preview -->
                <?php if (!empty($book['cover'])): ?>
                    <div style="margin-bottom: 1rem;">
                        <img src="<?php echo htmlspecialchars($book['cover']); ?>" alt="Current Cover" style="height: 100px; border-radius: 4px; border: 1px solid #e2e8f0;">
                    </div>
                <?php endif; ?>

                <div style="display: flex; gap: 1rem; margin-bottom: 0.5rem;">
                    <label style="cursor: pointer; display: flex; align-items: center;">
                        <input type="radio" name="cover_type" value="url" checked onchange="toggleCoverInput()"> 
                        <span style="margin-left: 5px;">Image URL</span>
                    </label>
                    <label style="cursor: pointer; display: flex; align-items: center;">
                        <input type="radio" name="cover_type" value="upload" onchange="toggleCoverInput()"> 
                        <span style="margin-left: 5px;">Upload File</span>
                    </label>
                </div>

                <div id="input-url">
                    <input type="text" id="img" name="cover_url" placeholder="https://example.com/cover.jpg" value="<?php echo (strpos($book['cover'], 'uploads/') === false) ? htmlspecialchars($book['cover']) : ''; ?>">
                </div>
                
                <div id="input-upload" style="display: none;">
                    <input type="file" name="cover_file" accept="image/*" style="padding: 0.5rem; border: 1px solid var(--slate-300); border-radius: 6px; width: 100%;">
                </div>
            </div>

            <script>
                function toggleCoverInput() {
                    const type = document.querySelector('input[name="cover_type"]:checked').value;
                    if (type === 'url') {
                        document.getElementById('input-url').style.display = 'block';
                        document.getElementById('input-upload').style.display = 'none';
                    } else {
                        document.getElementById('input-url').style.display = 'none';
                        document.getElementById('input-upload').style.display = 'block';
                    }
                }
            </script>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                Update Book
            </button>

            <div class="form-footer">
                <a href="list_books.php"><i class="fas fa-arrow-left"></i> Back to Books</a>
            </div>
        </form>
    </div>

    <?php if (isset($msg_type) && $msg_type == 'success'): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Updated!',
            text: 'Book information updated successfully.',
            showConfirmButton: false,
            timer: 2000
        });
    </script>
    <?php endif; ?>

</body>
</html>