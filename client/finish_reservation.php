<?php
session_start();
include "../db/connect.php";

if ($_SESSION['role'] != 'admin') {
    header("Location: client_reserve.php");
    exit;
}

if (isset($_POST['reservation_id'])) {
    $id = ($_POST['reservation_id']);
    $today = date("Y-m-d");
    $sql = "UPDATE reservations SET date_end = '$today' WHERE id = $id";
    mysqli_query($conn, $sql);
}

header("Location: client_reserve.php");
exit;
