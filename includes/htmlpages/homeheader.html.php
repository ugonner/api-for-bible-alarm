<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/bona/article/article.class.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/bona/user/user.class.php";
include_once $_SERVER["DOCUMENT_ROOT"].'/bona/product/product.class.php';
include_once $_SERVER["DOCUMENT_ROOT"].'/bona/notification/notification.class.php';
include_once $_SERVER["DOCUMENT_ROOT"].'/bona/facility/facility.class.php';

$headuser = new user();
$headarticle = new article();
$headproduct = new product();
$headnotification = new Notification();

$headfacility = new facility();
$headfacilitycategories = $headfacility->getCategories();

$headproductcategories = $headproduct ->getCategories();
$headcategories = $headarticle ->getCategories();
$headroles = $headuser ->getRoles();
if(!isset($_SESSION)){
    session_start();
}
//update user last activity;
    if(isset($_SESSION["userid"])){
        $id = $_SESSION["userid"];
        $email = $_SESSION["email"];
        $property = "lastactivity";
        $value = date("YmdHis");

        $headuser->editUser($id,$email,$property,$value);
    }


//get active persons;
if(isset($_GET["getactiveusers"])){
    if(isset($_GET["headpgn"])){
        $headpgn = $_GET["headpgn"];
    }else{
        $headpgn = 0;
    }

    $interval = 300;
    $time = date("YmdHis");

    $liveusers = $headuser->getActiveUsers($time,$interval);
    $countactiveusers = count($liveusers);
    $headlimit = 10;
    $headno_of_pages = $countactiveusers / $headlimit;
    if($countactiveusers>0){
        for($i = $headpgn; $i < $countactiveusers; $i++){
            $newusers[] = $liveusers[$i];
        }
        $activeusers = $newusers;

        if(isset($_GET["headpgn"])){
            $users = $activeusers;
            $title = "Live Users";
            include_once $_SERVER["DOCUMENT_ROOT"]."/bona/user/users.html.php";
            exit();
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="keywords" content="">
    <?php if(!empty($description)){
        echo '<meta name="description" content=" '.$description.' ">
<title> '.$title.' </title>';
    }else{
        echo '<meta name="description" content="'.$title.'">
    <title> '.$title.' </title>';
    }
    ?>
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="">

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 

    <!-- style for menu-->
    <style>
        select{
            background: #111111;
        }
        a, a:hover{color: #ffffff; text-decoration: none;}

        ul.slideuptabs{
            list-style: none;
            margin: 0;
            padding: 0;
            position: relative;
            text-align: left; /* change to "center" or "right" to align differently */
            border-bottom: 10px solid #555555; /* bottom border */
            background: #404040;
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#d8d8d8',GradientType=0 );
        }


        ul.slideuptabs li{
            display: inline;
        }

        ul.slideuptabs li:first-of-type{
            margin-left: 10px;
        }

        ul.slideuptabs a{
            position: relative;
            display: inline-block;
            overflow: hidden;
            color: white; /* font color */
            text-decoration: none;
            padding: 8px 20px;
            font-size: 14px; /* font size */
            font-weight: bold;
            vertical-align: bottom;
            -webkit-transition: color 0.5s; /* transition property and duration */
            -moz-transition: color 0.5s;
            transition: color 0.5s;
        }

        ul.slideuptabs a span{
            position: relative;
            z-index: 10;
        }

        ul.slideuptabs a::before{
            content: '';
            color: white;
            display: block;
            position: absolute;
            width: 100%;
            height: 100%;
            background: #555555; /* tab background */
            left: 0;
            top: 110%; /* extra 10% is to account for shadow dimension */
            box-shadow: -2px 2px 10px rgba(255,255,255,.5) inset;
            border-radius: 15px 15px 0 0 / 12px 12px; /* oval shaped border for top-left and top-right corners */
            -webkit-transition: top 0.5s; /* transition property and duration */
            -moz-transition: top 0.5s;
            transition: top 0.5s;
        }

        ul.slideuptabs a:hover{
            color: white; /* hover color */
        }

        ul.slideuptabs a:hover::before{
            top: 0; /* slide tab up */
        }

        /****** Responsive Code ******/

        @media screen and (max-width: 640px) {

            ul.slideuptabs li:first-of-type{
                margin-left: 0;
            }
            ul.slideuptabs li{display: block;}
        }


    </style>

    <!--[if lte IE 8]-->
    <style type="text/css">

        ul.slideuptabs a:hover{
            color: black; /* hover color for IE8 where tabs don't slide up */
        }

    </style>
    <!--[endif]-->
<!-- end style for menu-->
</head>
<style>
    .headiconsdiv{
        text-align: center;
    }
    .headicons{
        font-size: 2em;
    }
</style>
<body style="color:#ffffff; background-color: black;">
<div class="container-fluid">
<div>
    <h4>
        <a href="/">
            <b>AG</b>mall<br>
            <i>
                <small style="color: red;">
                    ...Got it? ...Get It
                    </i></small>
        </a>
    </h4>
</div>
<div class="nav navbar navbar-toggle" data-toggle="collapse" data-target="#menu1">
    <button>
    <span class='glyphicon glyphicon-th-large'></span>
    </button>
</div>
<div class=" collapse navbar-collapse" id="menu1">
    <ul class="slideuptabs">
        <li><a href="/"><span>Home</span></a></li>
        <li><a href="/bona/article/blog.html.php"><span>Blog At Our Forum</span></a></li>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="">
                    Products <span class="caret"></span></a>
            <ul class="dropdown-menu" style="background-color: #000000; color: lightcyan;">
                <?php foreach($headproductcategories as $headproductcategory):?>
                    <li><a href="/bona/product/?gpbc&cid=<?php echo $headproductcategory["id"]; ?>&categoryname=<?php echo $headproductcategory["name"]; ?>">
                            <?php echo $headproductcategory["name"]; ?>
                    </a> </li>
                <?php endforeach;?>
            </ul>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="">
                News <span class="caret"></span></a>
            <ul class="dropdown-menu" style="background-color: #000000; color: lightcyan;">
                <?php foreach($headcategories as $headcategory):?>
                    <li><a href="/bona/article/?gabc&cid=<?php echo $headcategory["id"]; ?>&categoryname=<?php echo $headcategory["name"]; ?>">
                            <?php echo $headcategory["name"]; ?>
                        </a> </li>
                <?php endforeach;?>
            </ul>
        </li>

        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="">
                Agro Facilities <span class="caret"></span></a>
            <ul class="dropdown-menu" style="background-color: #000000; color: lightcyan;">
                <?php foreach($headfacilitycategories as $hfc):?>
                    <li><a href="/bona/facility/?gfbc&cid=<?php echo $hfc["id"]; ?>">
                            <?php echo $hfc["name"]; ?>
                        </a> </li>
                <?php endforeach;?>
            </ul>
        </li>
        <li><a href="/bona/product/?getproducts"><span>Buy</span></a></li>
        <li><a href="/"><span>Contact Us</span></a></li>
    </ul>
</div>
</div>
<div>
        <div>
        <div class='col-xs-4 headiconsdiv'>
        <span class='glyphicon glyphicon-send headicons'></span>
            <br><a href="/bona/product/productform.html.php">
                <b>Sell Product <i>Free!</i></b>
            </a>
        </div>
          
        <div class='col-xs-4 headiconsdiv'>
            
        </div>
        
        <div class='col-xs-4 headiconsdiv'>
        <span class='glyphicon glyphicon-briefcase headicons'></span>
            <br><a href="/bona/user">
                <b> Your Business Profile</b>
            </a>
        </div>
        </div>
        

        <?php if(!isset($_SESSION['userid'])):?>
        <div>
        <div class='col-xs-4 headiconsdiv'>
        <span class='glyphicon glyphicon-log-in headicons'></span>
            <br><a href='/bona/user/'>Log In</a>
        </div>
        
        <div class='col-xs-4 headiconsdiv'>
        </div>
        
        <div class='col-xs-4 headiconsdiv'>
        <span class='glyphicon glyphicon-off headicons'></span>
            <br>
            <a href='/bona/user/registration.html.php'>
                Register, Easy
            </a>
        </div>
        </div>
        <?php endif; ?>

        <?php if(!empty($_SESSION["mycart"])):?>
            <?php $mycartitems = count($_SESSION["mycart"]);?>
            <div>
            <div class='col-xs-4 headiconsdiv'>
            <a href="/bona/transaction/?emptycart" style="color: #ffffff;">
                <button type="button" style="border: 0; background-color: transparent;">
                    <<b><span class="glyphicon glyphicon-shopping-cart" style="color: red;"></span>
                        <br>Empty Cart</b>
                </button>
            </a>
            </div>
        
            <div class='col-xs-4 headiconsdiv'>
            </div>    
            
            <div class='col-xs-4 headiconsdiv'>
            <a href="/bona/transaction/?getcart" style="color: #ffffff;">
                <button type="button" style="border: 0; background-color: transparent;">
                    <b><span class="glyphicon glyphicon-shopping-cart"></span>
                        <br>Cart
                        <span class="badge text-superscript"><?php echo $mycartitems; ?></span>
                    </b>
                </button>
            </a>
            </div>
            </div>
        <?php else: ?>
        <div class='col-xs-4 headiconsdiv'>
        </div>
        
        <div class='col-sm-4 headiconsdiv'>
        <a href="/bona/transaction/?getcart" style="color: #ffffff;">
            <button type="button" style="border: 0; background-color: transparent;">
                <b><span class="glyphicon glyphicon-shopping-cart"></span>
                    <br>Cart
                </b>
            </button>
        </a>
        </div>
        <div class='col-xs-4 headiconsdiv'>
        </div>
        
        <?php endif; ?>

</div>
<!-- end of container div -->
</div>