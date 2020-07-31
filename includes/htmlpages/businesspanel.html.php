<div>
<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/bona/transaction/transaction.class.php";
if(isset($user)){
    $uid = $user["id"];
}else{
    $uid = $_SESSION["userid"];
}


$sql = "SELECT count(*) FROM product WHERE userid = :userid";
try{
    $regdb = new Dbconn();
    $stmt = $regdb->dbcon->prepare($sql);
    $stmt->bindParam(":userid",$uid);

    $stmt ->execute();
    $productscounter = $stmt->fetch();
    $productscount = $productscounter[0];
}catch(PDOException $e){
    $error= "unable to get products count";
    $error2 = $e->getMessage();
    include_once $_SERVER["DOCUMENT_ROOT"]."/bona/includes/errors/error.html.php";
    exit;
}
$transaction = new Transaction();
$customercount = $transaction->getCountCustomers($uid)[0];

?>

<?php if(isset($_SESSION["userid"]) && $_SESSION["userid"] == $uid ){
//check for new requests transactions notifications from user lasttransaction count
$requestscount = $headnotification->getCountUserRequests($uid)[1];
$sql = 'SELECT lastrequestscount FROM user WHERE id = '.$uid;

$db = new Dbconn();
$dbh = $db->dbcon;
try{
    $stmt = $dbh -> prepare($sql);;

    $stmt -> execute();
    $requestcount2 = $stmt -> fetch();

}
catch(PDOException $e){
    $error = "Unable TO count requests notification counter";
    $error2 = $e -> getMessage();
    include_once $_SERVER['DOCUMENT_ROOT'].'/bona/includes/errors/error.html.php';
    exit();
}
$requests = $requestscount - $requestcount2[0];


//check for new transactions notifications from user lasttransaction count
$salescount1 = $headnotification->getCountUserSales($uid);
$salescount = $salescount1[0] + $salescount1[1];
$sql = 'SELECT lastsalescount FROM user WHERE id = '.$uid;

$db = new Dbconn();
$dbh = $db->dbcon;
try{
    $stmt = $dbh -> prepare($sql);;

    $stmt -> execute();
    $salescount2 = $stmt -> fetch();

}
catch(PDOException $e){
    $error = "Unable TO count sales transactions notification counter";
    $error2 = $e -> getMessage();
    include_once $_SERVER['DOCUMENT_ROOT'].'/bona/includes/errors/error.html.php';
    exit();
}
$sales = $salescount - $salescount2[0];

$totalsalesrequests = $salescount1[0];
}
?>
<h3 class="page-header">Business Panel:</h3>
<div>
    <h4 class="page-header">
    <a href="/bona/transaction/?customers&uid=<?php echo($uid); ?>">
        <button type="button" class="btn-lg" style="background: transparent; border: 0;">
            Customers <b><i>[ <?php echo $customercount;?> ]</i></b>
        </button>
    </a>
    </h4>

    <h4 class="page-header">
        <a href="/bona/product/?getuserproducts&uid=<?php echo($uid); ?>">
            <button type="button" class="btn-lg" style="background: transparent; border: 0;">
                Products <b><i>[ <?php echo $productscount;?> ]</i></b>
            </button>
        </a>
    </h4>
</div>
<?php if($uid == $_SESSION["userid"]):?>
<div>
<!-- display cash flow-->
    <h4 class="page-header">Your Cash Book:
    <br><small>Figures Are Calculated Based On Booked Supply / Requests that have been CONFIRMED by the customer And Current  Product Prices</small></h4>
<div style="padding-left: 40px;">
    <div>
        <h5>
            Total Cash In: <b>N<?php echo number_format($user['cashinflow'],2);?></b><br>
            <small>Last PayIn: <?php echo date("Y m d l, h:i:s");?></small>
        </h5>
    </div>

    <div>
            <h5>
            Total Cash Out: <b>N<?php echo number_format($user['cashoutflow'],2);?></b><br>
                <small>Last PayOut: <?php echo date("Y m d l, h:i:s");?></small>
            </h5>

    </div>
    <h5>
        Turn-Over: &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $user['totalsales'];?> Products Sold<br>
    <small>Total No Of Goods Sold (Confirmed)</small>
    <a href='/bona/product/?gupbt&uid=<?php echo $uid;?>'>
        <button type='button' style='background: transparent; border: 0;'>
           View Your Highest Selling Products
        </button>
    </a>
    </h5>
    <h5>
        Buy Interests: &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $totalsalesrequests;?> Requests<br>
    <small>Requests For My Products</small>
    </h5>
</div>
    <h4 class="page-header">Notifications</h4>
    <div style="padding-left: 40px;">
    <h5>
        <a href="/bona/notification/?sales" style="color: lightgray;">
            <span class="glyphicon glyphicon-shopping-cart" style="color: red;"></span>
            My Sales <span style="background: red;" class="badge"><?php echo($sales);?></span>
        </a>
    </h5>
    <h5>
        <a href="/bona/notification/?requests" style="color: lightgray;">
            <span class="glyphicon glyphicon-shopping-cart" style="color: red;"></span>
            My Requests <span style="background: red;" class="badge"><?php echo($requests);?></span>
        </a>
    </h5>
    </div>

</div>
<?php endif;?>
</div>