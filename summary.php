<?php

session_start();

if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
    header('location:login.php');
    exit;
}

require_once('TrxnFactory.php');

$currUserName = $_SESSION['user']['fname'].' '.$_SESSION['user']['lname'];

$summary = TrxnFactory::getTrxnSummary($_SESSION['user']['id']);
$trxns   = $summary['trxns'];
$total   = $summary['sum'];

?>

<link rel="stylesheet" type="text/css" href="commonstyle.css" />

<style>

#trxnsummary ul{
    list-style: none;
    margin: 0;
    padding: 0;
}

#trxnsummary .trxnrow li{
    display: inline-block;
    width: 100px;
    border: 1px solid #aaa;
    margin: 0;
    padding: 0;
    padding-left: 5px;
    text-transform: capitalize;
    background: #fff;
}

#trxnsummary .earn li{
    background: #9AFF9A;
    border: 1px solid #548B54;
}

#trxnsummary .header li{
    font-weight: bold;
    background: #ddd;
}

#trxnsummary .trxnrow .empty{
    border: 1px solid white;
}

#addTrxnForm{
    width: 600px;
}

#addTrxnForm input[type="submit"]{
    margin:0;
}

</style>

<h3>Budget for <?=$currUserName?></h3>

<form method='POST' action='AddTrxn.php' class='budget-grid' id='addTrxnForm'>
	<label class='budget-unit size1of2' for='desc'>Describe your transaction breifly (optional): </label>
	<input class='budget-unit size1of2' type='text' name='desc' />
	<br/>
	<label class='budget-unit size1of2' for='amount'>Amount: </label>
	<input class='budget-unit size1of6' type='number' name='amount' />
	<br/>
	<label class='budget-unit size1of2' for='category'>Category: </label>
	<select class='budget-unit' name='category'>
		<option value='1'>Food</option>
		<option value='2'>Clothes</option>
		<option value='3'>Movies</option>
		<option value='4'>Stationery</option>
		<option value='5'>Travel</option>
		<option value='6'>Electronics</option>
		<option value='7'>Home</option>
		<option value='8'>Health</option>
		<option value='9'>Beauty</option>
		<option value='10'>Sports</option>
		<option value='11'>Other</option>
		<option value='12'>Salary</option>
	</select>
	<br/>
	<label class='budget-unit size1of2' for='trxntype'>Type: </label>
	<select class='budget-unit' name='trxntype'>
		<option value='1'>Spend</option>
		<option value='2'>Earn</option>
	</select>
    <br/>
    <input type='submit' value='Submit' />
</form>

<br/>

<h4>Summary</h4>
<div id='trxnsummary'>
    <ul>
        <li>
            <ul class='trxnrow header'>
                <li>Category</li>
                <li>Description</li>
                <li>Amount</li>
            </ul>
        </li>
        <?php foreach ($trxns as $trxn) { ?>
        <li>
            <ul class='trxnrow <?php if($trxn->type=='earn'){ echo 'earn';}?>'>
                <li><?=$trxn->category?></li>
                <li><?=$trxn->description?></li>
                <li><?=(($trxn->type=='earn')?'-':'').'$'.number_format($trxn->amount, 2);?></li>
            </ul>
        </li>
        <?php } ?>
        <li>
            <ul class='trxnrow header'>
                <li class='empty'></li>
                <li>Total spent: </li>
                <li><?='$'.number_format($total, 2);?></li>
            </ul>
        </li>
    </ul>
</div>

<br/>
<br/>
<a href='logout.php'>Logout</a>

<?php

die();

?>