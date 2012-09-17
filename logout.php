<?php

session_start();

if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']) {
    unset($_SESSION['isLoggedIn']);
}

header('location:login.php');
exit;

?>