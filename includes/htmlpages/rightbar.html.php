
<div>

<div style="clear: both;">
    <br><br>
    <button id="getactiveusersbutton" type="button" class="btn-block" style="background-color: transparent; border: 0;" >
        <span class="glyphicon glyphicon-comment"></span> Chat Online Users
    </button>

    <div style="padding: 10px;" id="getactiveusersdiv">

    </div>
    <?php if(isset($_SESSION["userid"])):?>
        <div class="page-header">
        <h4 class="text-capitalize">Welcome, <?php echo $_SESSION["userdata"]["firstname"];?></h4>

        <h6 class="text-right">
            <a href="/bona/user/index.php?logout">
                <button class="btn-danger btn-sm" style="background-color: transparent;
                border: 0;">
                    Logging Out!
                </button>
            </a>
        </h6>
        </div>

        <div style="float: left; margin: 10px;">
            <img src="<?php echo $_SESSION["userdata"]["profilepic"];?>" class="img-circle"
                style="width:80px; height: 80px;"/>
        </div>
        <div style="font-size: 0.8em;">
            <?php $rusermsg = new Usermessage();
            if($unreadmsgs = $rusermsg ->getUnreadMessages($_SESSION["userid"],10,0)){
                $count = $unreadmsgs["count"];
            }else{
                $count = 0;
            }?>
            <?php
            $uid = $_SESSION["userid"];

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

            //check user notifications for posts/articles/comments;
            $notificationcount = $headnotification->getCountAllUserNotifications($uid);
            $sql = 'SELECT lastnotificationcount FROM user WHERE id = '.$uid;

            $db = new Dbconn();
            $dbh = $db->dbcon;
            try{
                $stmt = $dbh -> prepare($sql);;

                $stmt -> execute();
                $notificationcount2 = $stmt -> fetch();

            }
            catch(PDOException $e){
                $error = "Unable TO count notification counter";
                $error2 = $e -> getMessage();
                include_once $_SERVER['DOCUMENT_ROOT'].'/bona/includes/errors/error.html.php';
                exit();
            }
            $notifs = $notificationcount - $notificationcount2[0];

            //check for notifications on user posts;
            $userpostnotificationcount = $headnotification->getCountNotificationOnUserPosts($uid);
            $sql = 'SELECT lastuserpostnotificationcount FROM user WHERE id = '.$uid;

            $db = new Dbconn();
            $dbh = $db->dbcon;
            try{
                $stmt = $dbh -> prepare($sql);;

                $stmt -> execute();
                $userpostnotificationcount2 = $stmt -> fetch();

            }
            catch(PDOException $e){
                $error = "Unable TO count notification counter";
                $error2 = $e -> getMessage();
                include_once $_SERVER['DOCUMENT_ROOT'].'/bona/includes/errors/error.html.php';
                exit();
            }
            $userpostnotifs = $userpostnotificationcount - $userpostnotificationcount2[0];


            ?>
            <div class="toggle" data-target="#transactiondiv" data-toggle="collapse">
                <h4 class="btn">
                    My Transactions <span style="background: green;" class="badge" ><?php echo $sales + $requests; ?></span>
                </h4>
            </div>
            <div class="collapse" id="transactiondiv">
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

            <?php if($notifs > 0):?>
                <a href="/bona/notification/?getusernotifications" style="color: lightgray;">
                    <span class="glyphicon glyphicon-folder-close"></span>
                    New Notifications <span class="badge"><?php echo($notifs);?></span>
                </a><br>
            <?php endif;?>
            <?php if($userpostnotifs > 0):?>
                <a href="/bona/notification/?getuserpostnotifications" style="color: lightgray;">
                    <span class="glyphicon glyphicon-filter"></span>
                    New Notifications On My Posts <span class="badge"><?php echo($userpostnotifs);?></span>
                </a><br>
            <?php endif;?>
            <a href="/bona/user/usermessage/index.php?getunreadmessages" style="color: lightgray;">
                <span class="glyphicon glyphicon-envelope"></span>
                       Unread Messages <span class="badge"><?php echo($count);?></span>
            </a><br>


            <a href="/bona/product/index.php?getuserproducts&uid=<?php echo $_SESSION["userid"];?>" style="color: #ffffff;">
                <span class="glyphicon glyphicon-pencil"></span>
                My products
            </a><br>

            <a href="/bona/article/index.php?getuserarticles&uid=<?php echo $_SESSION["userid"];?>" style="color: #ffffff;">
                    <span class="glyphicon glyphicon-pencil"></span>
                    My Articles
            </a><br>

            <a href="/bona/article/index.php?gca" style="color: #ffffff;">
                <span class="glyphicon glyphicon-pencil"></span>
                Commented Articles

            </a><br>
            <a href="/bona/product/index.php?gca" style="color: #ffffff;">
                <span class="glyphicon glyphicon-pencil"></span>
                Commented products

            </a><br>
        </div>
    <?php else:?>
        <h4 class="page-header" style="clear: both;">Welcome Guest!</h4>
        <h5>You Can Register Here</h5>
           <b><a href="/bona/user/registration.html.php">
               <span class="glyphicon glyphicon-user"></span>
                   <i>Easy!</i> Just Click
           </a></b>
        <h5 class="text-center" style="color: red;">OR</h5>
        <b><a href="/bona/user/index.php">
            <span class="glyphicon glyphicon-log-in"></span> Sign In
        </a></b>
    <?php endif;?>
        <script type="text/javascript">
            $("#getactiveusersbutton").click(function(){
                $("#getactiveusersdiv").empty();
                $("#getactiveusersdiv").append("Loading Users...");
                $("#getactiveusersdiv").load("/bona/user/?getactiveusers");
            });
        </script>

    </div>
    <div class="row">
        <h3 class="page-header">Latest News / Articles</h3>
        <?php $sql = "SELECT count(id) FROM article";
        $db = new Dbconn();
        try{
            $stmt = $db ->dbcon->prepare($sql);
            $stmt->execute();
            $count1 = $stmt->fetch();
            $count = $count1[0];
        }catch(PDOException $e){
            $error = 'unable to count articles';
            echo $error ." ". $e->getMessage();
            echo $error ."<br> ". $e->getMessage();
        }
        $amtperpage = 10;
        $no_of_pages = ceil($count /$amtperpage);
        if(isset($_GET["pgn"])){
            $pgn = $_GET["pgn"];
        }else{
            $pgn = 0;
        }
        ?>
        <?php $rarticle = new article();
        $rarticles = $rarticle->getArticles($amtperpage,$pgn);?>
        <?php for($i =0; $i<$amtperpage; $i++):?>
            <?php
            if(isset($rarticles[$i])):?>
            <div style="clear: both;">
                <img src="<?php echo($rarticles[$i]["articleimagedisplayname"]);?>" style="width: 70px;
                height: 80px; float: left; margin: 5px;"/>
                <h5><?php echo($rarticles[$i]["title"]);?></h5>
                <h6><?php echo(date("M d. h:i a", strtotime($rarticles[$i]["dateofpublication"])));?></h6>
                <h6>
                    <a href="/bona/article/index.php?artid=<?php echo($rarticles[$i]["articleidentifier"]);?>&gaid=<?php echo($rarticles[$i][0]);?>">
                        Comment Full Story
                    </a>
                </h6>
            </div>
        <?php endif;?>
        <?php endfor;?>
        <ul class="pagination">
        <?php if($no_of_pages > 1){
            echo("<h3>See More <span style='color: red;'>>>></span></h3>");
            for($i=0; $i < $no_of_pages; $i++):?>
                <li <?php if($i == $pgn){echo("class='active'");}?>>
                    <a href="/bona/article/?getarticles&pgn=<?php echo($i);?>"><?php echo($i);?></a> </li>
            <?php endfor;
        } ?>
        </ul>
    </div>
</div>