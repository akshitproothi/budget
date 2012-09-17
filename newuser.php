<?php

session_start();

require_once('DbFactory.php');

if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']) {
    header('location:summary.php');
    exit;
}

if (
    isset($_POST['username'])
    && isset($_POST['password'])
    && isset($_POST['fname'])
    && isset($_POST['lname'])
) {
    $sql = "INSERT INTO tb_user (`username`, `password`, `fname`, `lname`, `datetime_created`, `datetime_updated`) VALUES (:username, :password, :fname, :lname, NOW(), NOW())";
    $parameters = array('username'=>$_POST['username'], 'password'=>sha1($_POST['password']), 'fname'=>$_POST['fname'], 'lname'=>$_POST['lname']);
    $datatypes  = array('username'=>'s', 'password'=>'s', 'fname'=>'s', 'lname'=>'s');
    $result = DbFactory::queryDb($sql, $parameters, $datatypes);
    
    if (isset($result['newid']) && $result['newid'] > 0) {
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['user']['id'] = $result['newid'];
        $_SESSION['user']['username'] = $_POST['username'];
        $_SESSION['user']['fname'] = $_POST['fname'];
        $_SESSION['user']['lname'] = $_POST['lname'];
        header('location:summary.php');
        exit;
    }
}

?>

<form method='POST' action='newuser.php'>
    <label for='username'>Username: </label>
    <input type='text' name='username' id='username' />
    <br/>
    <label for='password'>Password: </label>
    <input type='password' name='password' id='password' />
    <br/>
    <label for='fname'>First Name: </label>
    <input type='text' name='fname' id='fname' />
    <br/>
    <label for='lname'>Last Name: </label>
    <input type='text' name='lname' id='lname' />
    <br/>
    <input type='submit' value='Submit' />
</form>

<?php

die();

?>