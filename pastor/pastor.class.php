<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/api/includes/db/connect2.php';

class Pastor{
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


    public function getPastor($pastorid){
        $sql = "SELECT pastor.id AS pastorid,user.id AS userid, user.firstname,
                user.surname, user.profilepic,ministry.id AS ministryid,
                 ministry.name AS ministryname FROM pastor
                INNER JOIN ministry ON pastor.ministryid = ministry.id
                INNER JOIN user ON pastor.userid = user.id
                WHERE pastor.id = :pastorid
        UNION SELECT pastoruser.userid,user.id AS userid, user.firstname,
                user.surname , user.profilepic, 1,'ministry'
                FROM pastoruser INNER JOIN user ON pastoruser.userid = user.id
                WHERE pastoruser.pastorid = :pastorid2
                ";

        /*$sql = "1SELECT pastor.id AS pastorid,user.id AS userid, user.firstname, user.surname, user.profilepic,ministry.id AS ministryid, ministry.name AS ministryname FROM pastor INNER JOIN ministry ON pastor.ministryid = ministry.id INNER JOIN user ON pastor.userid = user.id WHERE pastor.id = :pastorid UNION SELECT pastoruser.userid,user.id AS userid, user.firstname, user.surname , user.profilepic, 1,'ministry' FROM pastoruser INNER JOIN user ON pastoruser.userid = user.id WHERE pastoruser.pastorid = :pastorid2
                ";*/
        /*$sql = "(SELECT pastor.id AS pastorid, pastor.name AS pastorname, user.id AS userid, user.firstname AS firstname FROM pastor
                INNER JOIN user ON pastor.adminid = user.id WHERE pastor.id = :pastorid UNION SELECT pastor.id, pastor.name, user.id, user.firstname FROM pastor INNER JOIN pastoruser ON pastor.id = pastoruser.pastorid INNER JOIN user ON pastoruser.userid = user.id WHERE pastor.id = 2
                ";*/
        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":pastorid", $pastorid);
            $stmt->bindParam(":pastorid2", $pastorid);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                $pastorandmembers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $pastorandmembers;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR UNABLE TO pastor details ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "messagee"=> $message ));
            exit();

        }

    }


    public function getPastors(){

        $sql = "SELECT pastor.id AS pastorid, userid, user.firstname, user.surname, user.profilepic,
                ministry.id AS ministryid,ministry.name AS ministryname
                FROM pastor INNER JOIN user ON pastor.userid = user.id
                INNER JOIN ministry ON pastor.ministryid = ministry.id
                ORDER BY pastor.id DESC";

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                $pastor = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $pastor;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR UNABLE TO pastor ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "messagee"=> $message ));
            exit();

        }

    }

//join pastor;
    public function joinPastor($userid,$pastorid){


        $sql = "INSERT INTO pastoruser (userid, pastorid) VALUES (:userid, :pastorid)";

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":userid", $userid);
            $stmt->bindParam(":pastorid", $pastorid);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();
            if($rowscount > 0){
                return true;

            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR: you are already following this pastor';
            $results = "0";
            echo json_encode(array("results"=> $results, "messagee"=> $message ));
            exit();

        }

    }
    //leave pastor

    public function leavePastor($userid,$pastorid){


        $sql = "DELETE FROM pastoruser WHERE userid = :userid AND pastorid = :pastorid";

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":userid", $userid);
            $stmt->bindParam(":pastorid", $pastorid);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                return true;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR UNABLE TO unregister to pastor ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "messagee"=> $message ));
            exit;

        }

    }

//get user's pastor
    public function getUserPastors($userid){

        $limit = 20;
        $countsql = "SELECT pastorid,user.firstname, user.surname, user.profilepic AS pastorpic FROM pastoruser
                INNER JOIN user ON pastoruser.pastorid = user.id ";

        if($count = $this->getCountOfItems($countsql,"pastoruser.userid",$userid)[0]){
            $GLOBALS["ApiInput"]["noofpages"] = $count / $limit;
        }else{
            $GLOBALS["ApiInput"]["noofpages"] = 1;
        }

        if(!empty($GLOBALS["ApiInput"]["pgn"])){
            $offset = ($GLOBALS["ApiInput"]["pgn"]) * $amtperpage;
        }else{
            $offset = 0;
        }

        $sql = "SELECT pastorid,user.firstname, user.surname,  user.profilepic AS profilepic FROM pastoruser
                INNER JOIN user ON pastoruser.pastorid = user.id
                WHERE pastoruser.userid = :userid ORDER BY pastoruser.pastorid DESC LIMIT ".$offset." , ".$limit;


        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":userid", $userid);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                $pastor = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $pastor;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR UNABLE TO user pastor ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "message"=> $message ));
            exit();

        }

    }


//add pastor;
    public function addPastor($adminid,$ministryid){

        //update urer roleid;
        $sql = "UPDATE user SET roleid = 1 WHERE id = :adminid";
        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":adminid", $adminid);
            $stmt -> execute();

        }
        catch(PDOException $e){
            $message = 'SQL ERROR: user is already a pastor'.$e->getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "message"=> $message ));
            exit();

        }

        //add to pastor table;
        $sql = "INSERT INTO pastor (userid, ministryid) VALUES (:adminid, :ministryid)";

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":adminid", $adminid);
            $stmt->bindParam(":ministryid", $ministryid);
            $stmt -> execute();
            $pastorid = $this->db->lastInsertId();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                /*if(!$this->joinpastor($adminid,$pastorid)){
                    $message = 'pastor created but could not add you as a member, please try again';
                    $results = "0";
                    echo json_encode(array("results"=> $results, "message"=> $message ));
                    exit();
                }*/
                return true;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR: user is already a pastor';
            $results = "0";
            echo json_encode(array("results"=> $results, "message"=> $message ));
            exit();

        }

    }
    //leave pastor

    public function removePastor($adminid,$pastorid){


        $sql = "DELETE FROM pastor WHERE userid = :adminid AND id = :pastorid";

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":adminid", $adminid);
            $stmt->bindParam(":pastorid", $pastorid);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                return true;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR no pastor deleted ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "message"=> $message ));
            exit();

        }

    }
}
//end of class;
?>