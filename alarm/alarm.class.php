<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/api/includes/db/connect2.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/api/push/push.class.php';

class Alarm{
    private $ApiUrl = "http://api.bible.org/";
    public function __construct(){
        $dbh = new Dbconn();
        $this -> db = $dbh->dbcon;
    }


    //get COUNT FOR PAGINATION PURPOSES;
    public function getCountOfItems($countersql, $property, $value){

        $sql = $countersql ." WHERE ".$property." = :value";
        try{
            $stmt = $this -> db -> prepare($countersql);
            $stmt->bindParam(":value", $value);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                $count = $stmt->fetch();
                return $count;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR UNABLE TO COUNT ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "message"=> $message ));
            exit();

        }

    }

//get all alerts;

    public function getGroupAlarms($groupid){

        $limit = 20;
        $countsql = "SELECT count(*) FROM groupalarm ";

        if($count = $this->getCountOfItems($countsql,"groupid",$groupid)[0]){
            $GLOBALS["ApiInput"]["noofpages"] = $count / $limit;
        }else{
            $GLOBALS["ApiInput"]["noofpages"] = 1;
        }

        if(!empty($GLOBALS["ApiInput"]["pgn"])){
            $offset = ($GLOBALS["ApiInput"]["pgn"]) * $amtperpage;
        }else{
            $offset = 0;
        }

        $sql = "SELECT id,alarmtext,title,setdate, alarmtypeid
                FROM groupalarm WHERE groupid = :groupid ORDER BY id DESC LIMIT ".$offset.", ".$limit;

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":groupid", $groupid);
            $stmt -> execute();
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                $alarms = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $alarms;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR UNABLE TO GET group alarm text ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "messagee"=> $message ));
            exit();

        }
        //fetch quotation text should be set during set alarm

    }
//end of getgroupalarm;

    public function getGroupAlarmsByType($alarmtypeid,$groupid){

        $limit = 20;
        if(!empty($GLOBALS["ApiInput"]["pgn"])){
            $offset = ($GLOBALS["ApiInput"]["pgn"]) * $amtperpage;
        }else{
            $offset = 0;
        }

        $sql = "SELECT groupalarm.id AS id,alarmtext,title,setdate,groups.name AS groupname,groupalarm,alarmtypeid
                FROM groupalarm INNER JOIN groups ON groups.id = groupalarm.groupid
                WHERE groupid = :groupid AND alarmtypeid = :alarmtypeid ORDER BY id DESC LIMIT ".$offset.", ".$limit;

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":groupid", $groupid);
            $stmt->bindParam(":alarmtypeid", $alarmtypeid);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

                if($rowscount > 0){
                    $alarms = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    return $alarms;
                }else{
                    return FALSE;
                }


            }
            catch(PDOException $e){
                $message = 'SQL ERROR UNABLE TO GET group alarm text ' . $e -> getMessage();
                $results = "0";
                echo json_encode(array("results"=> $results, "message"=> $message ));
                exit();

            }
            //fetch quotation text should be set during set alarm

    }
//end of getgroupalarm;

//getall pastor alarm;

    public function getPastorAlarm($pastorid){

        $limit = 20;
        $countsql = "SELECT count(*) FROM pastoralarm
                     INNER JOIN user ON pastoralarm.pastorid = user.id ";

        if($count = $this->getCountOfItems($countsql,"psstorid",$pastorid)[0]){
            $GLOBALS["ApiInput"]["noofpages"] = $count / $limit;
        }else{
            $GLOBALS["ApiInput"]["noofpages"] = 1;
        }

        if(!empty($GLOBALS["ApiInput"]["pgn"])){
            $offset = ($GLOBALS["ApiInput"]["pgn"]) * $amtperpage;
        }else{
            $offset = 0;
        }

        $sql = "SELECT pastoralarm.id,pastoralarm.alarmtext,pastoralarm.title,pastoralarm.setdate, pastoralarm.alarmtypeid,
                user.firstname, user.surname
                FROM pastoralarm INNER JOIN user ON pastoralarm.pastorid = user.id
                WHERE pastorid = :pastorid ORDER BY id DESC LIMIT ".$offset.", ".$limit;

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":pastorid", $pastorid);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                $alarmtext = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $alarmtext;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR UNABLE TO GET Paator alarm text all' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "messagee"=> $message ));
            exit();

        }
        //fetch quotation text should be set during set alarm

    }
//getpastoralarm;

    public function getPastorAlarmByType($alarmtypeid,$pastorid){
        //check if alarmtexttype is verse;

        $sql = "SELECT pastoralarm.id,pastoralarm.alarmtext,pastoralarm.title,pastoralarm.setdate, pastoralarm.alarmtypeid,
                user.firstname, user.surname
                FROM pastoralarm INNER JOIN user ON pastoralarm.pastorid = user.id
                WHERE pastorid = :pastorid AND alarmtypeid = :alarmtypeid ORDER BY id DESC LIMIT 20";

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":pastorid", $pastorid);
            $stmt->bindParam(":alarmtypeid", $alarmtypeid);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                $alarmtext = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $alarmtext;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR UNABLE TO GET Paator alarm text BY TYPE' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "messagee"=> $message ));
            exit();

        }
        //fetch quotation text should be set during set alarm

    }
//end of getgroupalarm;

//set alarm text;

    public function setGroupAlarm($groupid,$alarmtypeid,$alarmtext,$alarmtitle){

       //CREATE THE ALARM;
        $sql = 'INSERT INTO groupalarm (groupid, alarmtypeid, alarmtext, title, setdate)
         VALUES (:groupid,:alarmtypeid, :alarmtext, :alarmtitle,NOW())';

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":groupid", $groupid);
            $stmt->bindParam(":alarmtext", $alarmtext);
            $stmt->bindParam(":alarmtitle", $alarmtitle);
            $stmt->bindParam(":alarmtypeid", $alarmtypeid);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                //notify users;;
                $sql = "SELECT pushid, groups.name AS groupname FROM user
                        INNER JOIN groupuser ON groupuser.userid = user.id
                        INNER JOIN groups ON groupuser.groupid = groups.id
                        WHERE groupid = :groupid";

                try{
                    $stmt = $this -> db -> prepare($sql);
                    $stmt->bindParam(":groupid", $groupid);
                    $stmt -> execute();
                    while($row = $stmt->fetch( PDO::FETCH_ASSOC)){
                        $grouppushids[] = $row["pushid"];
                        $groupnames[] = $row["groupname"];
                    }

                    if(!empty($grouppushids)){
                        $groupname = $groupnames[0];
                        $push = new push();
                        $data =  array('notificationtype'=> "group new alert notification");
                        $push->sendPush($grouppushids, $alarmtitle,"New Word Alert From ".$groupname, $data);
                    }

                }catch (PDOException $e){
                    $message = 'SQL ERROR UNABLE TO ger group pids ' . $e -> getMessage();
                    $results = "0";
                    echo json_encode(array("results"=> $results, "messagee"=> $message ));
                    exit();
                }
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR UNABLE TO SET GROUP alarm ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "messagee"=> $message ));
            exit();

        }

    }
//end of setgroupalarm;

//set pastor alarm;

    public function setPastorAlarm($pastorid,$alarmtypeid,$alarmtext,$alarmtitle){

        //CREATE THE ALARM;
        $sql = 'INSERT INTO pastoralarm (pastorid, alarmtypeid, alarmtext, title, setdate)
         VALUES (:pastorid,:alarmtypeid, :alarmtext, :alarmtitle,NOW())';

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":pastorid", $pastorid);
            $stmt->bindParam(":alarmtext", $alarmtext);
            $stmt->bindParam(":alarmtitle", $alarmtitle);
            $stmt->bindParam(":alarmtypeid", $alarmtypeid);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                return true;
            }else{
                return FALSE;
            }
        }
        catch(PDOException $e){
            $message = 'SQL ERROR UNABLE TO SET PASTOR alarm ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "messagee"=> $message ));
            exit();

        }

    }


//edit alarm;
    public function editAlarm($settertypeid,$alarmtexttype,$alarmtext,$alarmid){
        if($settertypeid == 1){
            $alarmtable = "groupalarm";
        }else{
            $alarmtable = "pastoralarm";
        }
        $sql = "UPDATE ".$alarmtable." SET ".$alarmtexttype." = :alarmtext WHERE id = :alarmid";

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":alarmid", $alarmid);
            $stmt->bindParam(":alarmtext", $alarmtext);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                return true;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR UNABLE TO edit pastor alarm text ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "messagee"=> $message ));
            exit();

        }

    }
//end of edit pastor  alarm;

}
?>