<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/api/includes/helpers/cors.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/api/includes/helpers/inputsanitizer.php';

require_once $_SERVER['DOCUMENT_ROOT'].'/api/ministry/ministry.class.php';

//for test;
/* $ministry = new ministry();
echo json_encode($ministry->getMinistry(1));
exit;*/

//get user prayer ministries;
if(isset($input["getministry"])){
    $ministryid = $input["ministryid"];
    $ministry = new Ministry();
    $message = " Ministry fetched successfully ";
    if(!$results = $ministry->getMinistry($ministryid)){
        $message="No ministries, plesse check back";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}

//get user prayer ministries;
if(isset($input["getministries"])){


    $ministry = new Ministry();
    $message = " ministries fetched successfully ";
    if(!$results = $ministry->getMinistries()){
        $message="No ministries, plesse check back";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}



//ADD ministry;

//join ministry;
if(isset($input["addministry"])){
//get user id;
    if(isset($_SESSION["userid"])){
        $uid = $_SESSION["userid"];
    }else{
        $message = "You Are Not logged in, please lOGIN first";
        $results = "0";
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
    }

    $ministry = new ministry();
    $message = "You've created" .$input["ministryname"]. "ministry successfully ";
    if(!$results = $ministry->addministry($input["ministryname"],$input["ministryaddress"],$uid,$input["ministryabout"])){
        $message="Unable to create this ministry, you may not have permissions for this";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}

//edit ministry;

//join ministry;
if(isset($input["editministry"])){
//get user id;
    if(isset($_SESSION["userid"])){
        $uid = $_SESSION["userid"];
    }else{
        $message = "You Are Not logged in, please lOGIN first";
        $results = "0";
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
    }

    $ministry = new Ministry();
    $message = "You've edited this ministry successfully ";
    if(!$results = $ministry->editMinistry($input["property"],$input["value"],$input["ministryid"])){
        $message="Unable to remove this ministry, you may not be the current admin of this ministry";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}

//delete ministry

//join ministry;
if(isset($input["removeministry"])){
//get user id;
    if(isset($_SESSION["userid"])){
        $uid = $_SESSION["userid"];
    }else{
        $message = "You Are Not logged in, please lOGIN first";
        $results = "0";
        echo json_encode(array("results"=>$results, "message"=>$message));
        exit();
    }

    $ministry = new ministry();
    $message = "You've deleted this ministry successfully ";
    if(!$results = $ministry->removeministry($uid,$input["ministryid"])){
        $message="Unable to remove this ministry, you may not be the current admin of this ministry";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}
?>