<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/api/includes/helpers/cors.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/api/includes/helpers/inputsanitizer.php';

require_once $_SERVER['DOCUMENT_ROOT'].'/api/prayergroup/prayergroup.class.php';

//for test;
/* $group = new group();
echo $group->getGroup(1);
exit;*/

//get user prayer groups;
if(isset($input["getgroup"])){
    $groupid = $input["groupid"];
    $group = new group();
    $message = " Group fetched successfully ".$input["groupid"];
    if(!$results = $group->getGroup($groupid)){
        $message="No groups, plesse check back";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}

//get user prayer groups;
if(isset($input["getgroups"])){


    $group = new group();
    $message = " Groups fetched successfully ";
    if(!$results = $group->getGroups()){
        $message="No groups, plesse check back";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}

//get user prayer groups;
if(isset($input["getusergroups"])){

//GET USSE ID;
    if(isset($_SESSION["userid"])){
        $uid = $_SESSION["userid"];
    }else{
        $message = "You Are Not Identified With This Profile, lOGIN As Owner";
        $results = "0";
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
    }

    $group = new group();
    $message = "Your Groups fetched successfully ";
    if(!$results = $group->getUserGroups($uid)){
        $message="Looks like you are not registered with any group, Unable to get groups";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}


//join group;
if(isset($input["joingroup"])){
//get user id;
    if(isset($_SESSION["userid"])){
        $uid = $_SESSION["userid"];
    }else{
        $message = "You Are Not logged in, please lOGIN first";
        $results = "0";
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
    }

    $group = new group();
    $message = "You've joined this group successfully ";
    if(!$results = $group->joinGroup($uid,$input["groupid"])){
        $message="Unable to register you to this group, group may not exist anylonger";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}

//leave groups;
//LOGIN;
if(isset($input["leavegroup"])){
//get user id;
    if(isset($_SESSION["userid"])){
        $uid = $_SESSION["userid"];
    }else{
        $message = "You Are Not logged in, please lOGIN first";
        $results = "0";
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
    }


    $group = new group();
    $message = "You've Left this group successfully ";
    if(!$results = $group->leaveGroup($uid,$input["groupid"])){
        $message="Unable to leave this group, group may not exist anylonger";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}

//ADD GROUP;

//join group;
if(isset($input["addgroup"])){
//get user id;
    if(isset($_SESSION["userid"])){
        $uid = $_SESSION["userid"];
    }else{
        $message = "You Are Not logged in, please lOGIN first";
        $results = "0";
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
    }

    $group = new group();
    $message = "You've created" .$input["groupname"]. "group successfully ";
    if(!$results = $group->addGroup($uid,$input["groupname"])){
        $message="Unable to create this group, you may not have permissions for this";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}

//delete group

//join group;
if(isset($input["removegroup"])){
//get user id;
    if(isset($_SESSION["userid"])){
        $uid = $_SESSION["userid"];
    }else{
        $message = "You Are Not logged in, please lOGIN first";
        $results = "0";
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
    }

    $group = new group();
    $message = "You've deleted this group successfully ";
    if(!$results = $group->removeGroup($uid,$input["groupid"])){
        $message="Unable to remove this group, you may not be the current admin of this group";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}
?>