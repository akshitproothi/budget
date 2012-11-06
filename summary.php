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

$categories = TrxnFactory::getCategories();
$trxnTypes  = TrxnFactory::getTypes();

?>

<link rel="stylesheet" type="text/css" href="commonstyle.css" />

<style>

body {
	background: #ccc;
	margin: 0;
	padding: 0;
}

.mainContainer{
	width: 900px;
	margin: 0 auto;
	background: #fff;
}

.content{
	padding: 30px;
}

.content h1{
    margin: 0;
}

.header{
    margin: 0 0 2em 0;
}

.header a{
    text-align: right;
}

.content h3{
    margin: 0;
}

.blockHeader{
	border: 1px solid #DDD;
	display: inline-block;
	padding: 0.4em;
	border-bottom: none;
	border-top-right-radius: 5px;
    border-top-left-radius: 5px;
    background: #EEE;
}

#trxnsummary ul{
    list-style: none;
    margin: 0;
    padding: 0;
}

#trxnsummary .trxnrow li{
    display: inline-block;
    width: 275px;
    border: 1px solid #aaa;
    margin: 0;
    padding: 0;
    padding-left: 5px;
    text-transform: capitalize;
    background: #fff;
    margin-right: -5px;
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
    border: 1px solid #DDD;
    padding: 1em;
}

#addTrxnForm input[type="submit"]{
    margin:0;
}

</style>

<div class='mainContainer'>
	<div class='content'>
		<div class='header budget-grid'>
			<h1 class='budget-unit size7of8'>Budget for <?=$currUserName?></h1>
			<a class='budget-unit size1of8' href='logout.php'>Logout</a>
		</div>
		
		<div class='blockHeader'>
            <h3>Add a new Entry</h3>
		</div>
		<form method='POST' action='AddTrxn.php' class='budget-grid' id='addTrxnForm'>
			<label class='budget-unit size1of2' for='desc'>Describe your transaction breifly (optional): </label>
			<input class='budget-unit size1of2' type='text' name='desc' />
			<br/>
			<label class='budget-unit size1of2' for='amount'>Amount: </label>
			<input class='budget-unit size1of6' type='number' name='amount' />
			<br/>
			<label class='budget-unit size1of2' for='category'>Category: </label>
			<select class='budget-unit' name='category'>
			<?php foreach ($categories as $cat) {?>
                <option value='<?=$cat->id?>'><?=ucfirst($cat->name)?></option>
            <?php } ?>
			</select>
			<br/>
			<label class='budget-unit size1of2' for='trxntype'>Type: </label>
			<select class='budget-unit' name='trxntype'>
			<?php foreach ($trxnTypes as $t) {?>
                <option value='<?=$t->id?>'><?=ucfirst($t->name)?></option>
            <?php } ?>
			</select>
		    <br/>
		    <br/>
		    <input type='submit' value='Submit' />
		</form>
		
		<br/>
		
		<div class='blockHeader'>
		    <h3>Summary</h3>
		</div>
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
	</div>
</div>

<?php

die();

?>