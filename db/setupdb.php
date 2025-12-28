<?php

$server = "localhost";
$user = "root";
$pass = "";

$conn = mysqli_connect($server, $user, $pass); // CONNECT TO musql SERVER 

if (!$conn) {
    die(" Connection failed: " . mysqli_connect_error());
}
echo "Connected to MySQL succes<br>";



$sql = "CREATE DATABASE IF NOT EXISTS library "; // creat db
if (mysqli_query($conn, $sql)) {
    echo ("Database 'library' created<br>");
} else {
    echo ("Error creating database: " . mysqli_error($conn));
}

mysqli_select_db($conn, "library");


// creat table clients:
$sql = "
CREATE TABLE IF NOT EXISTS clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','client') DEFAULT 'client'
)";
if (mysqli_query($conn, $sql)) {
    echo " Table 'clients' created<br>";
} else {
    echo " Error clients: " . mysqli_error($conn) . "<br>";
}


//creat table books:
$sql = "
CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(30) NOT NULL,
    author VARCHAR(30) NOT NULL,
    cover VARCHAR(200) DEFAULT NULL,
    prix DECIMAL(10,2) NOT NULL 
);
";
if (mysqli_query($conn, $sql)) {
    echo "Table 'books' created.<br>";
} else {
    echo " Error books: " . mysqli_error($conn) . "<br>";
}


//creat table: reservation
$sql = "
CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    book_id INT NOT NULL,
    date_start TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_end DATE DEFAULT NULL,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);";
if (mysqli_query($conn, $sql)) {
    echo "Table 'reservation' created.<br>";
} else {
    echo "Error reservations: " . mysqli_error($conn) . "<br>";
}

//creat admin account with login email:admin@gmail.com and passowrd:123
$sql = "INSERT INTO clients (id, name, email, password, role)
VALUES (1, 'admin', 'admin@gmail.com', '123', 'admin');";
if (mysqli_query($conn, $sql)) {    
    echo "admin he is added successfully<br>";
} else {
    echo "error add admin: " . mysqli_error($conn) . "<br>";
}

//and this example of insert his add
$sql = "INSERT INTO clients (name, email, password, role) VALUES
    ('client1', 'client1@gmail.com',123, 'client'),
    ('client2', 'client2@gmail.com', 123, 'client')";
mysqli_query($conn, $sql);


$sql = "INSERT INTO books (title, author, cover, prix) VALUES
    ('The Name of the Wind', 'Patrick Rothfuss', 'https://m.media-amazon.com/images/I/91UzPegYyjL._AC_UF1000,1000_QL80_.jpg', 1),
    ('A Game of Thrones', 'George R.R. Martin', 'https://i.harperapps.com/hcanz/covers/9780007428540/y648.jpg',2),
    ('PHP: The Complete Reference', 'Steven Holzner', 'https://m.media-amazon.com/images/I/71GrLKDeGRL.jpg', 3)";
mysqli_query($conn, $sql);



$sql = "INSERT INTO reservations (client_id, book_id, date_start, date_end) VALUES
    (2, 1, '2025-11-01', '2025-11-05'),
    (2, 2, '2025-11-03', '2025-11-07'),
    (2, 2, '2025-11-05', '0000-00-00'),
    (3, 3, '2025-11-06', '0000-00-00'),
    (3, 2, '2025-11-07', '2025-11-10'),
    (3, 3, '2025-11-08', '0000-00-00'),
    (2, 1, '2025-11-09', '0000-00-00'),
    (2, 2, '2025-11-10', '2025-11-15'),
    (3, 1, '2025-11-11', '0000-00-00'),
    (3, 3, '2025-11-12', '0000-00-00')";
mysqli_query($conn, $sql);

mysqli_close($conn);
