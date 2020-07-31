<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/api/includes/helpers/cors.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/api/includes/helpers/inputsanitizer.php';

require_once $_SERVER['DOCUMENT_ROOT'].'/api/prayer/prayer.class.php';

//for test;
/* $group = new Prayer();
echo json_encode($group->getPrayer(1));
exit;*/

//get user prayer groups;
if(isset($input["getprayer"])){
    $prayerid = $input["prayerid"];
    $prayer = new Prayer();
    $message = " Prayer fetched successfully ";
    if(!$results = $prayer->getPrayer($prayerid)){
        $message="No prayer, plesse check back";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}


//get user prayer groups;
if(isset($input["getprayers"])){


    $prayer = new Prayer();
    $message = " Prayers fetched successfully ";
    if(!$results = $prayer->getPrayers()){
        $message="No prayers, plesse check back";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}


//get user prayer groups;
if(isset($input["getprayersbytypeid"])){

    $prayer = new Prayer();
    $message = " Prayers fetched successfully ";
    if(!$results = $prayer->getPrayersByTypeId($input["prayertypeid"])){
        $message="No prayers, plesse check back";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}

//get user prayer groups;
if(isset($input["getuserprayers"])){

//GET USSE ID;
    if(isset($_SESSION["userid"])){
        $uid = $_SESSION["userid"];
    }else{
        $message = "You Are Not Identified With This Profile, lOGIN As Owner";
        $results = "0";
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
    }

    $prayer = new Prayer();
    $message = "Your Prayers fetched successfully ";
    if(!$results = $prayer->getUserPrayerGroupPrayers($uid)){
        $message="Looks like you have not made any prayer requests in a group, Unable to get groups";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}

//get prayer followers;
if(isset($input["getprayerfollowers"])){
    $prayerid = $input["prayerid"];
    $prayer = new Prayer();
    $message = " Prayer followers got ";
    if(!$results = $prayer->getPrayerFollowers($prayerid)){
        $message="Looks like no one is currently on this prayer, plesse check back";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}

//join group;
if(isset($input["joinprayer"])){
//get user id;
    if(isset($_SESSION["userid"])){
        $uid = $_SESSION["userid"];
    }else{
        $message = "You Are Not logged in, please lOGIN first";
        $results = "0";
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
    }

    $prayer = new Prayer();
    $message = "You've joined in this prayer successfully ";
    if(!$results = $prayer->joinPrayer($input["prayerid"],$input["prayeractiontext"],$uid,$input["ownerid"],date("YmdHis"),$input["actiontypeid"])){
        $message="Unable to join this prayer, prayer may not exist anylonger";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}

//leave groups;
//LOGIN;
if(isset($input["leaveprayer"])){
//get user id;
    if(isset($_SESSION["userid"])){
        $uid = $_SESSION["userid"];
    }else{
        $message = "You Are Not logged in, please lOGIN first";
        $results = "0";
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
    }


    $prayer = new Prayer();
    $message = "You've reneged from this prayer successfully ";
    if(!$results = $prayer->leavePrayer($uid,$input["prayerid"])){
        $message="Unable to renege from this prayer, prayer may not exist anylonger";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();
}

//ADD GROUP;
if(isset($input["addprayer"])){
//get user id;
    if(isset($_SESSION["userid"])){
        $uid = $_SESSION["userid"];
    }else{
        $message = "You Are Not logged in, please lOGIN first";
        $results = "0";
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
    }

    $prayer = new Prayer();
    $message = "You've made a prayer request successfully ";
    if(!$results = $prayer->addPrayerGroupPrayer($input["prayertext"],$uid,$input["prayergroupid"],date("YmdHis"),$input["prayertypeid"],"N","N")){
        $message="Unable to make prayer request, you may not have permissions for this";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}

//EDIT PRAYER;

if(isset($input["editprayer"])){
//get user id;
    if(isset($_SESSION["userid"])){
        $uid = $_SESSION["userid"];
    }else{
        $message = "You Are Not logged in, please lOGIN first";
        $results = "0";
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
    }

    $prayer = new Prayer();
    $message = "You've edited this prayer successfully";
    if(!$results = $prayer->editPrayerGroupPrayer($input["property"],$input["value"],$input["prayerid"])){
        $message="Unable to edit this prayer, you may not have permissions for this";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}

//delete group
if(isset($input["removeprayer"])){
//get user id;
    if(isset($_SESSION["userid"])){
        $uid = $_SESSION["userid"];
    }else{
        $message = "You Are Not logged in, please lOGIN first";
        $results = "0";
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
    }

    $prayer = new Prayer();
    $message = "You've deleted this prayer successfully ";
    if(!$results = $prayer->removePrayer($uid,$input["prayerid"])){
        $message="Unable to remove this prayer, you may not be the owner of this prayer";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}

?>