<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/api/includes/db/connect2.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/api/prayer/prayer.class.php';

class group{
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

    public function getGroup($groupid){

/*      $limit = 20;
        $countsql = "(SELECT count(*) FROM prayer INNER JOIN user ON prayer.userid = user.id
                INNER JOIN groups ON prayer.prayergroupid = groups.id
                WHERE prayer.prayergroupid = ".$prayergroupid.")";

        if($count = $this->getCountOfItems($countsql)[0]){
            $GLOBALS["ApiInput"]["noofpages"] = $count / $limit;
        }else{
            $GLOBALS["ApiInput"]["noofpages"] = 1;
        }

        if(!empty($GLOBALS["ApiInput"]["pgn"])){
            $offset = ($GLOBALS["ApiInput"]["pgn"]) * $amtperpage;
        }else{
            $offset = 0;
        }*/

        $sql = "SELECT groups.id AS groupid, groups.name AS groupname, user.id AS userid,
                user.firstname AS firstname, user.profilepic as profilepic FROM groups
                INNER JOIN user ON groups.adminid = user.id
                WHERE groups.id = :groupid
        UNION SELECT groups.id, groups.name, user.id, user.firstname, user.profilepic as profilepic FROM groups
                INNER JOIN groupuser ON groups.id = groupuser.groupid
                INNER JOIN user ON groupuser.userid = user.id
                WHERE groups.id = :groupid2
                ";

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":groupid", $groupid);
            $stmt->bindParam(":groupid2", $groupid);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                $groupandmembers = $stmt->fetchAll(PDO::FETCH_ASSOC);

                //get groups;
                $prayer = new Prayer();
                if($prayers = $prayer->getPrayerGroupPrayers($groupid)){
                    //check if user is following any;
                    if(isset($_SESSION["userid"])){
                        $userid = $_SESSION["userid"];
                    }else{
                        $userid = 0;
                    }

                    for($i=0; $i< count($prayers); $i++){
                        if($prayer->hasUserActedOnPrayer($prayers[$i]["prayerid"], $userid)){
                            $prayers[$i]["isfollowingprayer"] = 1;
                        }else{
                            $prayers[$i]["isfollowingprayer"] = 0;
                        }
                    }
                }

                return array("groupandmembers"=>$groupandmembers, "prayers"=>$prayers);
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR UNABLE TO group details ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "messagee"=> $message ));
            exit();

        }

    }

    public function getGroups(){

        $limit = 20;
        $countsql = "SELECT count(*) FROM groups";

        try{
            $stmt = $this->db->prepare($countsql);
            $stmt->execute();
            $rowscount = $stmt->rowCount();
            if($rowscount > 0){
                $GLOBALS["ApiInput"]["noofpages"] = ($stmt->fetch()[0]) / $limit;
            }else{
                $GLOBALS["ApiInput"]["noofpages"] = 1;
            }

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


        $sql = "SELECT id, adminid, groups.name FROM groups ORDER BY id DESC LIMIT ".$offset.", ".$limit;

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                $groups = $stmt->fetchAll();
                return $groups;
            }else{
                return false;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR UNABLE TO groups ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "messagee"=> $message ));
            exit();

        }

    }

//join group;
    public function joinGroup($userid,$groupid){


        $sql = "INSERT INTO groupuser (userid, groupid) VALUES (:userid, :groupid)";

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":userid", $userid);
            $stmt->bindParam(":groupid", $groupid);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();
            if($rowscount > 0){
                return true;
                //cancelled in favor of getting data from front end;
                /*return array("groupid"=> $groupid, "groupname"=>$groupname);
                $sql = "SELECT groups.id AS groupid, groups.name AS groupname
                        FROM groups WHERE id = :groupid";

                try{
                    $stmt = $this -> db -> prepare($sql);
                    $stmt->bindParam(":groupid", $groupid);
                    $stmt -> execute();
                    $groupdata = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    return $groupdata;

                }catch (PDOException $e){
                    $message = 'SQL ERROR UNABLE TO ger group ' . $e -> getMessage();
                    $results = "0";
                    echo json_encode(array("results"=> $results, "messagee"=> $message ));
                    exit();
                }*/
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR UNABLE TO register to group ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "messagee"=> $message ));
            exit();

        }

    }

    //leave group

    public function leaveGroup($userid,$groupid){


        $sql = "DELETE FROM groupuser WHERE userid = :userid AND groupid = :groupid";

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":userid", $userid);
            $stmt->bindParam(":groupid", $groupid);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                return true;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR UNABLE TO unregister to group ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "messagee"=> $message ));
            exit();

        }

    }

//get user's groups
    public function getUserGroups($userid){

        $limit = 20;
        $countsql = "SELECT count(*) FROM groupuser
                    INNER JOIN groups ON groupuser.groupid = groups.id";

        if($count = $this->getCountOfItems($countsql,"groupuser.userid", $userid)[0]){
            $GLOBALS["ApiInput"]["noofpages"] = $count / $limit;
        }else{
            $GLOBALS["ApiInput"]["noofpages"] = 1;
        }

        if(!empty($GLOBALS["ApiInput"]["pgn"])){
            $offset = ($GLOBALS["ApiInput"]["pgn"]) * $amtperpage;
        }else{
            $offset = 0;
        }

        $sql = "SELECT groupid,groups.name AS groupname FROM groupuser
         INNER JOIN groups ON groupuser.groupid = groups.id WHERE groupuser.userid = :userid";

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":userid", $userid);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                $groups = $stmt->fetchAll();
                return $groups;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR UNABLE TO user groups ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "message"=> $message ));
            exit();

        }

    }


//add group;
    public function addGroup($adminid,$groupname){


        $sql = "INSERT INTO groups (adminid, name) VALUES (:adminid, :groupname)";

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":adminid", $adminid);
            $stmt->bindParam(":groupname", $groupname);
            $stmt -> execute();
            $groupid = $this->db->lastInsertId();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                if(!$this->joinGroup($adminid,$groupid)){
                    $message = 'Group created but could not add you as a member, please try again';
                    $results = "0";
                    echo json_encode(array("results"=> $results, "message"=> $message ));
                    exit();
                }
                return true;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR UNABLE TO create group ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "message"=> $message ));
            exit();

        }

    }
    //leave group

    public function removeGroup($adminid,$groupid){


        $sql = "DELETE FROM groups WHERE adminid = :adminid AND id = :groupid";

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":adminid", $adminid);
            $stmt->bindParam(":groupid", $groupid);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                return true;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR no groups deleted ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "message"=> $message ));
            exit();

        }

    }
}
//end of class;
?>