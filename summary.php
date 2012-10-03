<?php

session_start();

if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
    header('location:login.php');
    exit;
}

require_once('TrxnFactory.php');

$currUserName = $_SESSION['user']['fname'];

$summary = TrxnFactory::getTrxnSummary($_SESSION['user']['id']);
$trxns   = $summary['trxns'];
$total   = $summary['sum'];

?>

<style>

#trxnsummary ul{
    list-style: none;
}

#trxnsummary .trxnrow li{
    display: inline-block;
    width: 100px;
    border: 1px solid #aaa;
    margin: 0;
    padding: 0;
    padding-left: 5px;
    text-transform: capitalize;
}

#trxnsummary .header li{
    font-weight: bold;
}

#trxnsummary .trxnrow .empty{
    border: 1px solid white;
}

</style>

<h3>Hello to Budget Summary, <?=$currUserName?></h3>

<form method='POST' action='AddTrxn.php'>
	<label for='desc'>Describe your transaction breifly (optional): </label>
	<input type='text' name='desc' />
	<br/>
	<label for='amount'>Amount: </label>
	<input type='text' name='amount' />
	<br/>
	<label for='category'>Category: </label>
	<select name='category'>
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
		<option value='11'>Salary</option>
		<option value='12'>Other</option>
	</select>
	<br/>
	<label for='trxntype'>Type: </label>
	<select name='trxntype'>
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
                <li>Type</li>
                <li>Description</li>
                <li>Amount</li>
            </ul>
        </li>
        <?php foreach ($trxns as $trxn) { ?>
        <li>
            <ul class='trxnrow'>
                <li><?=$trxn->category?></li>
                <li><?=$trxn->type?></li>
                <li><?=$trxn->description?></li>
                <li><?='$'.number_format($trxn->amount, 2);?></li>
            </ul>
        </li>
        <?php } ?>
        <li>
            <ul class='trxnrow header'>
                <li class='empty'></li>
                <li class='empty'></li>
                <li>Total: </li>
                <li><?='$'.number_format($total, 2);?></li>
            </ul>
        </li>
    </ul>
</div>

<?php

die();

?>