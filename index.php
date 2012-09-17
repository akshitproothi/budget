<?php

session_start();

if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']) {
    header('location:summary.php');
    exit;
} else {
    header('location:login.php');
    exit;
}

?>