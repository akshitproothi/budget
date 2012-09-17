<?php

session_start();

if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
    header('location:login.php');
    exit;
}

require_once('DbFactory.php');

$currUserName = $_SESSION['user']['fname'];

?>

<h3>Hello to Budget Summary, <?=$currUserName?>

<?php

die();

?>