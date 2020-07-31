<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/api/includes/helpers/cors.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/api/includes/helpers/inputsanitizer.php';

require_once $_SERVER['DOCUMENT_ROOT'].'/api/alarm/alarm.class.php';
require_once $_SERVER['DOCUMENT_ROOT']. '/api/includes/helpers/mediafilehandler.php';

/*$alarm = new Alarm();
echo $alarm->setGroupAlarm(1,1,"manchi alerts",1);
exit;*/

if(isset($input["getgroupallalarms"])){
    /*    $GLOBALS["ApiInput"]["alarmtexttype"],
    $GLOBALS["ApiInput"]["groupid"]*/


    $alarm = new Alarm();
    $message = "alert text got successfully ";
    if(!$results = $alarm->getGroupAlarms($input["groupid"])){
        $message="no alarm text got ";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}


if(isset($input["getpastorallalarms"])){
    /*    $GLOBALS["ApiInput"]["alarmtexttype"],
    $GLOBALS["ApiInput"]["groupid"]*/


    $alarm = new Alarm();
    $message = "alert text got successfully ";
    if(!$results = $alarm->getPastorAlarm($input["pastorid"])){
        $message="no alarm text got ";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}
//LOGIN;
if(isset($input["getgroupalarmsByType"])){
    /*    $GLOBALS["ApiInput"]["alarmtexttype"],
    $GLOBALS["ApiInput"]["groupid"]*/


    $alarm = new Alarm();
    $message = "alert text got successfully ";
    if(!$results = $alarm->getGroupAlarmsByType($input["alarmtypeid"],$input["groupid"])){
        $message="no alarm text got ";
        $results = "0";

    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}

//get alarm text for pastor alarm;
if(isset($input["getpastoralarmbytype"])){

/*    $GLOBALS["ApiInput"]["alarmtexttype"],
$GLOBALS["ApiInput"]["pastorid"]  */

    $alarm = new Alarm();
    $message = "alarm text got successfully ";
    if(!$results = $alarm->getPastorAlarmByType($input["alarmtypeid"],$input["pastorid"])){
        $message="no alarm text got ".$GLOBALS["ApiInput"]["alarmtexttype"];
        $results = "0";
    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}
//set alarm by group admin;
if(isset($input["creategroupalarmtext"])){
/*    $GLOBALS["ApiInput"]["groupid"],
$GLOBALS["ApiInput"]["alarmtexttype"],
$GLOBALS["ApiInput"]["alarmtext"],
$GLOBALS["ApiInput"]["alarmtitle"]*/

    $alarm = new Alarm();
    $message = "alarm text set successfully ";
    if(!$results = $alarm->setGroupAlarm($input["groupid"],$input["alarmtypeid"],$input["alarmtext"],$input["alarmtitle"])){
        $message="alert not set";
        $results = "0";
    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();

}

//set pastor alarm;
//set alarm by pastor;;
if(isset($input["createpastoralarmtext"])){
    /*    $GLOBALS["ApiInput"]["groupid"],
    $GLOBALS["ApiInput"]["alarmtexttype"],
    $GLOBALS["ApiInput"]["alarmtext"]*/


    $alarm = new Alarm();
    $message = "alarm text set successfully ";
    if(!$results = $alarm->setPastorAlarm($input["pastorid"],$input["alarmtypeid"],$input["alarmtext"],$input["alarmtitle"])){
        $message="alarm not set ";
        $results = "0";
    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();
}

if(isset($input["editalarm"])){
/*    $GLOBALS["ApiInput"]["setertypeid"],
$GLOBALS["ApiInput"]["alarmtexttype"],
$GLOBALS["ApiInput"]["alarmtext"],
$GLOBALS["ApiInput"]["alarmid"]*/

    $alarm = new Alarm();
    $message = "alarm text edit successfully ".$GLOBALS["ApiInput"]["alarmtexttype"];
    if(!$results = $alarm->editAlarm($GLOBALS["ApiInput"]["setertypeid"],$GLOBALS["ApiInput"]["alarmtexttype"],$GLOBALS["ApiInput"]["alarmtext"],$GLOBALS["ApiInput"]["alarmid"])){
        $message="alarm not edited ".$GLOBALS["ApiInput"]["alarmtexttype"];
        $results = "0";
    }
    echo json_encode(array("results"=>$results,"message" => $message));
    exit();
}

?>
