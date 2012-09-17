<?php

session_start();

require_once('DbFactory.php');

$errorMessage = '';

if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']) {
    header('location:summary.php');
    exit;
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $sql = 'SELECT pk_user_id, username, fname, lname FROM tb_user WHERE username = :username AND password = :password LIMIT 1';
    $parameters = array('username'=>$_POST['username'], 'password'=>sha1($_POST['password']));
    $datatypes  = array('username'=>'s', 'password'=>'s');
    $result = DbFactory::queryDb($sql, $parameters, $datatypes);
    
    if (count($result) > 0 && $result[0]->username == $_POST['username']) {
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['user']['id'] = $result[0]->pk_user_id;
        $_SESSION['user']['username'] = $result[0]->username;
        $_SESSION['user']['fname'] = $result[0]->fname;
        $_SESSION['user']['lname'] = $result[0]->lname;
        header('location:summary.php');
        exit;
    } else {
        $errorMessage = 'Invalid Username or Password.';
    }
}

?>

<form method='POST' action='login.php'>
    <label for='username'>Username: </label>
    <input type='text' name='username' id='username' />
    <br/>
    <label for='password'>Password: </label>
    <input type='password' name='password' id='password' />
    <br/>
    <?php if ($errorMessage !== '') { 
        echo $errorMessage;
        echo '<br/>';
    }?>
    <input type='submit' value='Login' />
</form>

<a href='newuser.php'>
    <input type='button' value='Create New User' />
</a>

<?php

die();

?>