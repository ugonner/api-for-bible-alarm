<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/api/includes/helpers/cors.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/api/includes/helpers/inputsanitizer.php';

/*header("Access-Control-Allow-Origin: *");*/
require_once $_SERVER['DOCUMENT_ROOT'].'/api/user/user.class.php';
require_once $_SERVER['DOCUMENT_ROOT']. '/api/includes/helpers/encodepassword.php';
/*require_once $_SERVER['DOCUMENT_ROOT']. '/api/includes/helpers/mediafilehandler.php';*/


/*$user = new user();
$results = $user->isLoggedIn();
echo json_encode(array('results'=>$results));
exit;*/


//update token;
if(isset($input['updatepushid'])){
    if(!isset($_SESSION)){
        session_start();
    }

    if(!isset($_SESSION["userid"])){
        $results = "0";
        $message = "please log in first";
        echo json_encode(array("results"=>$results,"message" => $message));
        exit;
    }

    $email = $_SESSION["email"];
    $pushid = $input['pushid'] ;
    $message = "push profile updated";
    $user = new user();

    if(!$results = $user->updateUserPushId($pushid,$email)){
        $message = 'your push was not updated';
        echo json_encode(array("results"=>$results,"message" => $message));
        exit;
    }
}


//LOGIN;
if(isset($input["log"])){
    $user = new user();
    $message = "successful log in";
    if(!$results = $user->isLoggedIn()){
        $message="Please Login First With Correct Email And Password Pair ".$GLOBALS["ApiInput"]["log"];
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();
}

//update last activity;
if(isset($input["updateacitivity"])){
    session_start();
    if(isset($_SESSION["userid"])){
        $id = $_SESSION["userid"];
        $email = $_SESSION["email"];
        $property = "lastactivity";
        $value = time();
        $user = new user();
        $user->editUser($id,$email,$property,$value);
    }

}

//get wards;
if(isset($input["getroles"])){
    $message = 'successfull';
    $user = new user();
    if(!$results = $user ->getRoles()){
        $results = "0";
        $message = "failed to get roles";
    }

    echo json_encode(array("results"=>$results, "message"=> $message));
    exit();
}


//get user;

if(isset($input['getuser'])){
    $uid = $input['uid'];
    $message = "succeddfull getting user";
    $user = new User();
    if(!$results = $user -> getUser($uid)){
        $results = "0";
        $message = "Filed to get user data";
    }

    echo json_encode(array("results"=>$results));
    exit();
}

//get users in a in an lga;
if((isset($input["gulbr"])) && (isset($input["lid"]))){
    $lid = $input["lid"];
    $rid = $input["rid"];
    $user = new user();
    $message = "successfull getting users in lga";
    if(!$results = $user->getUsersInLGAByRole($lid,$rid)){
        $results = "0";
        $message = "failed getting users in lga";
    }
    echo json_encode(array("results"=>$results));
    exit();
}


//get users in an state by role;
if((isset($input["gusbr"])) && (isset($input["sid"]))){
    $sid = $input["sid"];
    $rid = $input["rid"];

    $user = new user();
    $message = "Successful";
    if(!$results = $user->getUsersInStateByRole($sid,$rid)){
        $results = "0";
        $message = "No User Found In This Category";
    }
    echo json_encode(array("results"=>$results, "message"=>$message));
    exit();
}


//edituser;
if(isset($input["edituser"])){
    if(isset($_SESSION["userid"])){
        $uid = $_SESSION["userid"];
    }else{
        $message = "You Are Not Identified With This Profile, lOGIN As Owner";
        $results = "0";
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
    }

    //handle location edit: state and lga ids;
    if($input["pty"] == 'location'){
        $lgaid = $input["value"]["lgaid"];
        $stateid = $input["value"]["stateid"];
        $user = new user();
        $message = "Location Edited successfully";
        $user->editUser($uid,"stateid",$stateid);
        if(!$results= $user->editUser($uid,"LGAid",$lgaid)){
            $message = $uid." failed to edit user ".$lgaid;
            $results = "0";
        }
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
    }

    if($input["pty"] == "profilepic"){
        $file = "/api/images/users/profilepic".$_SESSION["userid"]."id".time().".jpg";
        $filename = $_SERVER["DOCUMENT_ROOT"].$file;
        if(!file_put_contents($filename,$input["value"])){
            $message = "pic not stored";
            $results = "0";
            echo json_encode(array("results"=>$results, "message"=>$message));
            exit();
        }
        $pty = "profilepic";
        $value = "https://".$_SERVER["HTTP_HOST"].$file;
    }else{
        $pty = htmlspecialchars($input["pty"]);
        $value = htmlspecialchars($input["value"]);
    }
    /*$file="profilepic";
    $folder = "/bona/img/users/";
    if($profilepic=storeFile($file,$folder,'user image')){
        $pty = "profilepic";
        $value = $profilepic["displayname"];
    }*/
    $message = $uid ." Profile Information Successfully Edited";
    $user = new user();

    if(!$results= $user->editUser($uid,$pty,$value)){
        $message = $uid." failed to edit user ";
        $results = "0";
    }
    echo json_encode(array("results"=>$results, "message"=>$message));
    exit();
}

//registration data validation;
if(isset($input['register'])){
    if(!isset($_SESSION)){
        session_start();
    }
    //validate email;
    if(!preg_match('/[-!\.,@#\$%\^\&\*\()_\+=\/a-z0-9]+@[-a-z0-9\_\.,]+\.[-a-z0-9\_\.,]+/i'
        , $input["email"])){
        $results = "0";
        $message = 'This Email Is Not Set, Please Put A valid Email Address';
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
    }else{
        $email = htmlspecialchars($input['email']);
    }
/*    $day = $input['day'];
    $month = $input['month'];
    if((strlen($input['year'])==4) and ($input['year']!= '')){
        $year =  $input['year'];
    }else{
        $year = '1900';
    }
    $DOB= $year.'-'.$month.'-'.$day;*/

    //validate mobile number;
 if(isset($input["mobile"]) && (trim($input["mobile"])!= "")){
    if(isset($input["foreigner"])){
        if(empty($input["zip"])){
           $message = 'Zip code is empty, if you reside outside Nigeria Please enter a zipxode
           for your country of residence';
            $results = "0";
            echo json_encode(array("results"=>$results, "message"=>$message));
            exit();
        }else{
            $mobile = htmlspecialchars($input["zip"].substr($input["mobile"],1));
        }
    }else{
        $mobile = htmlspecialchars("234".substr($input["mobile"],1));
    }
 }else{
     $message = 'Mobile Number Is Not Set';
     $results = "0";
     echo json_encode(array("results"=>$results, "message"=>$message));
     exit();
 }
//validate public for mobile;
    if(isset($input["public"])){
        $public = "N";
    }else{
        $public = "Y";
    }

    $password = encodePassword($input["password"]);
    $firstname = htmlspecialchars($input['firstname']);
    $surname = htmlspecialchars($input['surname']);
    $dateofbirth = NULL;
    $gender = NULL;
    $about = "Just Me";
    $dateofregistration = date('YmdHis');
    $stateid = 38;
    $lgaid = 768;
    $roleid = 2;
    $rolenote = "An Enthusiast";
    $displayname = NULL;
//call to user class;
    $user = new user();
    if($results = $user ->registerUser($firstname,$surname,$email,$password,$mobile,$gender,
    $dateofbirth,$dateofregistration,$about,$stateid,$lgaid,$roleid,$rolenote,$public)){
        $message = "Congrats! ".$firstname." , You Have Successfully Registered";
        if(!isset($_SESSION)){
            session_start();
        }
        $_SESSION["email"] = $email;
        $_SESSION["password"] = $input["password"];

    }else{
        $message = "Failed registration: Email Already exists";
        $results = "0";
    }
    echo json_encode(array("results"=>$results, "message"=>$message));
    exit();
}
//above is the end of the rtgister;

//get user articles;
if(isset($input["getuserarticles"])){
    $uid = $input["uid"];
    if(isset($input["pgn"])){
        $pgn = $input["pgn"];
    }else{
        $pgn = 0;
    }

    $amtperpage = 10;
    $sql = 'SELECT count(id) FROM article WHERE userid = :userid';

    $db = new Dbconn();
    $dbh = $db->dbcon;
    try{
        $stmt = $dbh -> prepare($sql);
        $stmt->bindParam(":userid",$uid);
        $stmt -> execute();
        $countarticles = $stmt -> fetch();

    }
    catch(PDOException $e){
        $message = "Unable TO Count the user articles";
        $results = "0";
        $error2 = $e -> getMessage();
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
    }

    $no_of_pages = ceil($countarticles[0] / $amtperpage);

    $user = new user();
    if($UAs = $user->getUserArticles($uid,$amtperpage,$pgn)){
        $user = $UAs["user"];
        $message = "successful";
        $results = $UAs["userarticles"];
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
        }else{
        $results = "0";
        $message = "This Person Probably Has No Article To His Name OR It  Has Been deleted";
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
    }
}
//RESET PASSWORD;
if(isset($input["resetpassword"])){
    session_start();
    if(!($input["password"] == $_SESSION["password2"])){
        $message = "Your Old Password Did Not Match The Account's Password, If You
        Are The Owner Of This Account Please Contact the <a href='/bona/contact.html.php'>
        <b>Support Team </b> </a>";
        $results = "0";
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
    }

    $uid = $_SESSION["userid"];
    $email = $_SESSION["email"];
    $oldpassword = $input["oldpassword"];
    $newpassword = $input["password"];
    $user = new user();
    if($results= $user->resetPassword($uid,$email,$oldpassword,$newpassword)){
        $message = "Successfull";
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
    }else{
        $results = "0";
        $message = "An Error Occurred Resetting Password, Password Not Reset";
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
    }
}
//PUT AT LAST TO RECOVER PASSWORDS;


//GET ACTIVE USERS;
//get active persons;
if(isset($input["getactiveusers"])){
    if(isset($input["pgn"])){
        $pgn = $input["pgn"];
    }else{
        $pgn = 0;
    }

    $interval = 300;
    $time = date("YmdHis");
    $amtperpage = 10;
    $sql = 'SELECT count(id) FROM user WHERE ('.$time.' - lastactivity ) <= '.$interval;

    $db = new Dbconn();
    $dbh = $db->dbcon;
    try{
        $stmt = $dbh -> prepare($sql);
        $stmt -> execute();
        $countactiveusers = $stmt -> fetch();

    }
    catch(PDOException $e){
        $message = "Unable TO Count article";
        $results = "0";
        $error2 = $e -> getMessage();
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
    }

    $no_of_pages = $countactiveusers[0] / $amtperpage;
    $user = new user();
    if($results = $user->getActiveUsers($time,$interval,$amtperpage,$pgn)){
        $message = "successfull";
    }else{
        $results = "0";
        $message = "No One Is Currently Online, Check Again Soonest";
    }
    echo json_encode(array("results"=>$results, "message"=>$message));
    exit();
}

//for pages;


    //GET ACTIVE USERS;
//get active persons;
if(isset($input["seeallliveusers"])){
        if(isset($input["pgn"])){
            $pgn = $input["pgn"];
        }else{
            $pgn = 0;
        }

        $interval = 300;
        $time = time();
        $amtperpage = 10;
        $sql = 'SELECT count(id) FROM user WHERE ('.$time.' - lastactivity ) <= '.$interval;

        $db = new Dbconn();
        $dbh = $db->dbcon;
        try{
            $stmt = $dbh -> prepare($sql);
            $stmt -> execute();
            $countactiveusers = $stmt -> fetch();

        }
        catch(PDOException $e){
            $message = "users not counted";
            $results = "0";
            echo json_encode(array("results"=>$results, "message"=>$message));
            exit();
        }

        $no_of_pages = ceil($countactiveusers[0] / $amtperpage);
        $user = new user();
        if($results = $user->getActiveUsers($time,$interval,$amtperpage,$pgn)){
            $message = "Live Users";
            echo json_encode(array("results"=>$results, "message"=>$message));
            exit();
        }else{
            $message = "No Body Online Yet";
            $results = "0";
            echo json_encode(array("results"=>$results, "message"=>$message));
            exit();
        }

    }



/*
$user = new user();
//password recovery;
$user->recoverPassword();
//log in call;
if(!$user->isLoggedIn()){
    $error="Please Login First With Correct Email And Password Pair";
    include_once $_SERVER["DOCUMENT_ROOT"]."/bona/user/forms/loginform.html.php";
    exit();
}
$uid=$_SESSION["userid"];
header("Location:/bona/user/index.php?guid=".$uid);
exit();*/

?>