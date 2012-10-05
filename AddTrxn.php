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

$success = false;

if (!is_numeric($amount)) {
    $success = false;
} else {
    $newid = TrxnFactory::addTrxn($user, $desc, $amount, $category, $trxntype);
    if ($newid > 0) {
        $success = true;
    }
}
header('location:summary.php?status='.$success);
exit;

?>