<?php
session_start();


session_unset();

session_destroy();

setcookie("saved_email", "", time() - (3600), "/");

header("Location: ../main.php");
exit;
