<?php

session_start();

if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
    header('location:login.php');
    exit;
}

require_once('TrxnFactory.php');

$user       = $_SESSION['user']['id'];
$desc       = $_POST['desc'];
$amount     = $_POST['amount'];
$category   = $_POST['category'];
$trxntype   = $_POST['trxntype'];

TrxnFactory::addTrxn($user, $desc, $amount, $category, $trxntype);
header('location:summary.php');
exit;

?>