<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/api/includes/helpers/cors.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/api/includes/helpers/inputsanitizer.php';

require_once $_SERVER['DOCUMENT_ROOT'].'/api/pastor/pastor.class.php';

//for test;
/* $Pastor = new Pastor();
echo $Pastor->getUserPastors(1);
exit;*/

//get user prayer Pastors;
if(isset($input["getPastor"])){
    $Pastorid = $input["Pastorid"];
    $Pastor = new Pastor();
    $message = " Pastor fetched successfully ".$input["Pastorid"];
    if(!$results = $Pastor->getPastor($Pastorid)){
        $message = " No Pastor, plesse check back";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}

//get user prayer Pastors;
if(isset($input["getPastors"])){


    $Pastor = new Pastor();
    $message = " Pastors fetched successfully ";
    if(!$results = $Pastor->getPastors()){
        $message="No Pastors, plesse check back";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}

//get user prayer Pastors;
if(isset($input["getuserPastors"])){

//GET USSE ID;
    if(isset($_SESSION["userid"])){
        $uid = $_SESSION["userid"];
    }else{
        $message = "You Are Not Identified With This Profile, lOGIN As Owner";
        $results = "0";
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
    }

    $Pastor = new Pastor();
    $message = "Your Pastors fetched successfully ";
    if(!$results = $Pastor->getUserPastors($uid)){
        $message="Looks like you are not registered with any Pastor, Unable to get Pastors";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}


//join Pastor;
if(isset($input["joinPastor"])){
//get user id;
    if(isset($_SESSION["userid"])){
        $uid = $_SESSION["userid"];
    }else{
        $message = "You Are Not logged in, please lOGIN first";
        $results = "0";
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
    }

    $Pastor = new Pastor();
    $message = "You've joined this Pastor successfully ";
    if(!$results = $Pastor->joinPastor($uid,$input["Pastorid"])){
        $message="Unable to register you to this Pastor, Pastor may not exist anylonger";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}

//leave Pastors;
//LOGIN;
if(isset($input["leavePastor"])){
//get user id;
    if(isset($_SESSION["userid"])){
        $uid = $_SESSION["userid"];
    }else{
        $message = "You Are Not logged in, please lOGIN first";
        $results = "0";
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
    }


    $Pastor = new Pastor();
    $message = "You've Left this Pastor successfully ";
    if(!$results = $Pastor->leavePastor($uid,$input["Pastorid"])){
        $message="Unable to leave this Pastor, Pastor may not exist anylonger";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}

//ADD Pastor;

//join Pastor;
if(isset($input["addPastor"])){
//get user id;
    if(isset($_SESSION["userid"])){
        $uid = $_SESSION["userid"];
    }else{
        $message = "You Are Not logged in, please lOGIN first";
        $results = "0";
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
    }

    $Pastor = new Pastor();
    $message = "You've made a Pastor successfully ";
    if(!$results = $Pastor->addPastor($input["userid"],$input["ministryid"])){
        $message="Unable to create this Pastor, you may not have permissions for this";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}

//delete Pastor

//join Pastor;
if(isset($input["removePastor"])){
//get user id;
    if(isset($_SESSION["userid"])){
        $uid = $_SESSION["userid"];
    }else{
        $message = "You Are Not logged in, please lOGIN first";
        $results = "0";
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
    }

    $Pastor = new Pastor();
    $message = "You've deleted this Pastor successfully ";
    if(!$results = $Pastor->removePastor($uid,$input["Pastorid"])){
        $message="Unable to remove this Pastor, you may not be the current admin of this Pastor";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}
?>