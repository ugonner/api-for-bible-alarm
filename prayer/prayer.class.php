<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/api/includes/db/connect2.php';

class Prayer{
    public $db;

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


    public function getPrayer($prayerid){

        $sql = "(SELECT prayer.id AS prayerid, noofprayeractions, prayer.prayertext, prayer.prayertypeid,prayer.userid, dateofpublication, answered,
                user.firstname, user.surname, user.profilepic as profilepic, user.roleid, prayer.prayergroupid, groups.name AS groupname
                FROM prayer INNER JOIN user ON prayer.userid = user.id
                INNER JOIN groups ON prayer.prayergroupid = groups.id
                WHERE prayer.id = :prayerid)";

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":prayerid", $prayerid);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                $prayer = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $prayer[0]["dateofpublication"] = date("y M d l, h:i:s a", strtotime($prayer[0]["dateofpublication"]));
                if(isset($_SESSION["userid"])){
                    $userid = $_SESSION["userid"];
                    if($this->hasUserActedOnPrayer($prayerid, $userid)){
                        $prayer[0]["isfollowing"] = 1;
                    }else{
                        $prayer[0]["isfollowing"] = 0;
                    }
                }else{
                    $prayer[0]["isfollowing"] = 0;
                }
                return $prayer;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR UNABLE TO GET PRAYER ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "messagee"=> $message ));
            exit();

        }

    }


//get groups prayers
    public function getPrayerGroupPrayers($prayergroupid){

        $limit = 20;
        $countsql = "(SELECT count(*) FROM prayer INNER JOIN user ON prayer.userid = user.id
                INNER JOIN groups ON prayer.prayergroupid = groups.id )";

        if($count = $this->getCountOfItems($countsql,"prayer.prayergroupid",$prayergroupid)[0]){
            $GLOBALS["ApiInput"]["noofpages"] = $count / $limit;
        }else{
            $GLOBALS["ApiInput"]["noofpages"] = 1;
        }

        if(!empty($GLOBALS["ApiInput"]["pgn"])){
            $offset = ($GLOBALS["ApiInput"]["pgn"]) * $amtperpage;
        }else{
            $offset = 0;
        }

        $sql = "SELECT prayer.id AS prayerid, noofprayeractions, prayer.prayertext, prayer.prayertypeid,prayer.userid, dateofpublication, answered,
                user.firstname, user.surname,  user.profilepic AS profilepic,user.roleid, groups.name AS groupname
                FROM prayer INNER JOIN user ON prayer.userid = user.id
                INNER JOIN groups ON prayer.prayergroupid = groups.id
                WHERE prayer.prayergroupid = :prayergroupid
                ORDER BY prayer.id DESC LIMIT ".$offset.", ".$limit;

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":prayergroupid", $prayergroupid);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                $usergroupprayers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $usergroupprayers;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR UNABLE TO groups Prayers' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "message"=> $message ));
            exit();

        }


    }

    //check user action on prayer;


//get prayers followers
    public function getPrayerFollowers($prayerid){

        $limit = 20;
        $countsql = "SELECT count(*) FROM prayergroupprayeraction INNER JOIN user ON prayergroupprayeraction.userid = user.id
                INNER JOIN role ON user.roleid = role.id ";

        if($count = $this->getCountOfItems($countsql,"prayergroupprayeraction.prayerid",$prayerid)[0]){
            $GLOBALS["ApiInput"]["noofpages"] = $count / $limit;
        }else{
            $GLOBALS["ApiInput"]["noofpages"] = 1;
        }

        if(!empty($GLOBALS["ApiInput"]["pgn"])){
            $offset = ($GLOBALS["ApiInput"]["pgn"]) * $amtperpage;
        }else{
            $offset = 0;
        }

        $sql = "(SELECT prayerid, prayeractiontext, dateofpublication, user.id AS userid,
                user.firstname, user.surname,user.profilepic as profilepic,
                user.roleid, role.name AS rolename
                FROM prayergroupprayeraction INNER JOIN user ON prayergroupprayeraction.userid = user.id
                INNER JOIN role ON user.roleid = role.id
                WHERE prayergroupprayeraction.prayerid = :prayerid
                ORDER BY prayergroupprayeraction.id DESC LIMIT ".$offset." , ".$limit.")";

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":prayerid", $prayerid);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                $prayerfollowers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $i=0;
                while( $i<count($prayerfollowers)){
                    $prayerfollowers[$i]["dateofpublication"] = date("y, M d l, h:i:s a", strtotime($prayerfollowers[$i]["dateofpublication"]));
                    $i++;
                }
                return $prayerfollowers;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR UNABLE TO get prayer followers' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "message"=> $message ));
            exit();

        }

    }

//get user's groups
    public function getUserPrayerGroupPrayers($userid){
        $limit = 20;
        $countsql = "SELECT count(*) FROM prayer
                    INNER JOIN user ON prayer.userid = user.id
                    INNER JOIN groups ON prayer.prayergroupid = groups.id";

        if($count = $this->getCountOfItems($countsql,"prayer.userid",$userid)[0]){
            $GLOBALS["ApiInput"]["noofpages"] = $count / $limit;
        }else{
            $GLOBALS["ApiInput"]["noofpages"] = 1;
        }

        if(!empty($GLOBALS["ApiInput"]["pgn"])){
            $offset = ($GLOBALS["ApiInput"]["pgn"]) * $amtperpage;
        }else{
            $offset = 0;
        }


        $sql = "(SELECT prayer.id AS prayerid, noofprayeractions, prayer.prayertext,prayertypeid, dateofpublication, answered,
                user.firstname, user.surname, user.profilepic as profilepic, user.roleid, groups.name AS groupname
                FROM prayer INNER JOIN user ON prayer.userid = user.id
                INNER JOIN groups ON prayer.prayergroupid = groups.id
                WHERE prayer.userid = :userid
                ORDER BY prayer.id DESC LIMIT ".$offset." , ".$limit.")";

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":userid", $userid);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                $usergroupprayers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $usergroupprayers;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR UNABLE TO groups Prayers' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "message"=> $message ));
            exit();

        }

    }


    //get prayer;
    public function getPrayersByTypeId($prayertypeid){

        $limit = 20;
        $countsql = "SELECT count(*) FROM prayer WHERE prayertypeid = :prayertypeid";

        try{
            $stmt = $this->db->prepare($countsql);
            $stmt->bindParam(":prayertypeid",$prayertypeid);
            $stmt->execute();
            $counter = $stmt->fetch();
            $GLOBALS["ApiInput"]["noofpages"] = $counter[0] / $limit ;

        }catch (PDOException $e){
            $message = 'SQL ERROR UNABLE TO count prayers ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "message"=> $message ));
            exit();
        }


        if(!empty($GLOBALS["ApiInput"]["pgn"])){
            $offset = ($GLOBALS["ApiInput"]["pgn"]) * $amtperpage;
        }else{
            $offset = 0;
        }

        $sql = "(SELECT prayer.id AS prayerid, prayer.prayertext,prayertypeid, noofprayeractions, prayer.userid, dateofpublication, answered,
                user.firstname, user.surname,user.profilepic, user.roleid, groups.name AS groupname
                FROM prayer INNER JOIN user ON prayer.userid = user.id
                INNER JOIN groups ON prayer.prayergroupid = groups.id
                WHERE prayertypeid = :prayertypeid
                ORDER BY prayer.id DESC LIMIT ".$offset.", ".$limit.")";

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":prayertypeid",$prayertypeid);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                $prayers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $prayers;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR UNABLE TO prayers ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "message"=> $message ));
            exit();

        }

    }

    //get prayer;
    public function getPrayers(){

        $limit = 20;
        $countsql = "SELECT count(*) FROM prayer";

        try{
            $stmt = $this->db->prepare($countsql);
            $stmt->execute();
            $counter = $stmt->fetch();
            $GLOBALS["ApiInput"]["noofpages"] = $counter[0] / $limit ;

        }catch (PDOException $e){
            $message = 'SQL ERROR UNABLE TO count prayers ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "message"=> $message ));
            exit();
        }


        if(!empty($GLOBALS["ApiInput"]["pgn"])){
            $offset = ($GLOBALS["ApiInput"]["pgn"]) * $amtperpage;
        }else{
            $offset = 0;
        }

        $sql = "(SELECT prayer.id AS prayerid, prayer.prayertext,prayertypeid, noofprayeractions, prayer.userid, dateofpublication, answered,
                user.firstname, user.surname, user.profilepic as profilepic,user.roleid, groups.name AS groupname
                FROM prayer INNER JOIN user ON prayer.userid = user.id
                INNER JOIN groups ON prayer.prayergroupid = groups.id
                ORDER BY prayer.id DESC LIMIT ".$offset.", ".$limit.")";

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                $prayers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $prayers;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR UNABLE TO prayers ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "message"=> $message ));
            exit();

        }

    }

//check user action on prayer;
//get groups prayers
    public function hasUserActedOnPrayer($prayerid, $userid){

        $sql = "SELECT count(userid) FROM prayergroupprayeraction WHERE prayerid = :prayerid AND userid = :userid";

        $sql = "SELECT count(userid) FROM prayergroupprayeraction
                WHERE prayerid = :prayerid AND userid = :userid";
        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":prayerid", $prayerid);
            $stmt->bindParam(":userid", $userid);

            $stmt -> execute();
            $rowscount = $stmt ->fetch();

            if($rowscount[0] > 0){
                return true;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR UNABLE TO groups Prayers' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "message"=> $message ));
            exit();

        }


    }

//join group;
    public function joinPrayer($prayerid, $prayeractiontext, $userid, $ownerid, $dateofpublication,$actiontypeid){

        $sql = "INSERT INTO prayergroupprayeraction
         (prayerid, prayeractiontext, userid, ownerid, dateofpublication,actiontypeid)
          VALUES (:prayerid, :prayeractiontext, :userid, :ownerid, :dateofpublication, :actiontypeid)";

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":prayerid", $prayerid);
            $stmt->bindParam(":prayeractiontext", $prayeractiontext);
            $stmt->bindParam(":userid", $userid);
            $stmt->bindParam(":ownerid", $ownerid);
            $stmt->bindParam(":dateofpublication", $dateofpublication);
            $stmt->bindParam(":actiontypeid", $actiontypeid);

            $stmt -> execute();
            $rowscount = $stmt -> rowCount();
            if($rowscount > 0){
                $this->incrementTableValue("prayer","noofprayeractions",$prayerid);
                return true;

            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'Sorry: You are already praying on this:';
            $results = "0";
            echo json_encode(array("results"=> $results, "message"=> $message ));
            exit();

        }

    }

    //leave group
    public function leavePrayer($userid,$prayerid){


        $sql = "DELETE FROM prayergroupprayeraction WHERE userid = :userid AND prayerid = :prayerid";

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":userid", $userid);
            $stmt->bindParam(":prayerid", $prayerid);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                $this->decrementTableValue("prayer","noofprayeractions",$prayerid);
                return true;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR: Unable renege from this prayer ';
            $results = "0";
            echo json_encode(array("results"=> $results, "message"=> $message ));
            exit();

        }

    }




//add group;
    public function addPrayerGroupPrayer($prayertext, $userid, $prayergroupid, $dateofpublication,$prayertypeid, $answered, $public){


        $sql = "INSERT INTO prayer
         (prayertext, userid, prayergroupid,dateofpublication,prayertypeid,answered, public)
          VALUES (:prayertext, :userid, :prayergroupid, :dateofpublication,:prayertypeid, :answered, :public)";

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":prayertext", $prayertext);
            $stmt->bindParam(":userid", $userid);
            $stmt->bindParam(":prayergroupid", $prayergroupid);
            $stmt->bindParam(":dateofpublication", $dateofpublication);
            $stmt->bindParam(":prayertypeid", $prayertypeid);
            $stmt->bindParam(":answered", $answered);
            $stmt->bindParam(":public", $public);

            $stmt -> execute();
            $prayerid = $this->db->lastInsertId();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                return true;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR UNABLE TO create prayer ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "message"=> $message ));
            exit();

        }

    }


//add group;
    public function editPrayerGroupPrayer($property, $value, $prayerid){


        $sql = "UPDATE prayer SET ".$property." = '".$value."' WHERE id = :prayerid";
        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":prayerid", $prayerid);

            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                return true;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = $sql.' SQL ERROR: UNABLE TO EDIT THIS PRAYER'. $e->getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "message"=> $message ));
            exit();

        }

    }

    //remove prayer;
    public function removePrayer($userid,$prayerid){


        $sql = "DELETE FROM prayer WHERE userid = :userid AND id = :prayerid";

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":userid", $userid);
            $stmt->bindParam(":prayerid", $prayerid);

            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                return true;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR: no groups deleted ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "message"=> $message ));
            exit();

        }

    }


    //utility fxns;

//increment a table value;
    public function incrementTableValue($tablename, $tableproperty, $tableid){

        $sql = "UPDATE ".$tablename." SET ".$tableproperty." = ".$tableproperty."+ 1 WHERE id = :tableid";
        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":tableid", $tableid);

            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                return true;
            }else{
                return FALSE;
            }

        }
        catch(PDOException $e){
            $message = 'SQL ERROR: UNABLE TO increment table'.$e->getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "message"=> $message ));
            exit();

        }

    }

    //decrement table;
    public function decrementTableValue($tablename, $tableproperty, $tableid){

        $sql = "UPDATE ".$tablename." SET ".$tableproperty." = ".$tableproperty."- 1 WHERE id = :tableid";
        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":tableid", $tableid);

            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                return true;
            }else{
                return FALSE;
            }

        }
        catch(PDOException $e){
            $message = 'SQL ERROR: UNABLE TO decrement table'.$e->getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "message"=> $message ));
            exit();

        }

    }
}
//end of class;
?>