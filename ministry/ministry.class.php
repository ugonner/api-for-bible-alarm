<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/api/includes/db/connect2.php';

class Ministry{
    private $ApiUrl = "http://api.bible.org/";
    public function __construct(){
        $dbh = new Dbconn();
        $this -> db = $dbh->dbcon;
    }


    public function getMinistry($ministryid){
        $sql = "SELECT ministry.id AS ministryid, overseerid, name AS ministryname, ministry.address, ministry.profilepic,
                ministry.about as about, user.firstname, user.surname
                FROM ministry INNER JOIN user ON ministry.overseerid = user.id
                WHERE ministry.id = :ministryid";

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":ministryid", $ministryid);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                $ministry = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $ministry;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR UNABLE TO ministry details ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "message"=> $message ));
            exit();

        }

    }

    public function getMinistries(){


        $sql = "SELECT ministry.id AS ministryid, ministry.name AS ministryname
                FROM ministry ORDER BY ministry.id DESC";

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                $ministries = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $ministries;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR UNABLE TO ministries ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "message"=> $message ));
            exit();

        }

    }

//join pastor;

//add pastor;
    public function addMinistry($name,$address,$overseerid, $about){


        $sql = "INSERT INTO ministry (name, address, overseerid, about) VALUES (:name, :address, :overseerid, :about)";

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":address", $address);
            $stmt->bindParam(":overseerid", $overseerid);
            $stmt->bindParam(":about", $about);

            $stmt -> execute();

            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                return true;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR UNABLE TO create ministry ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "message"=> $message ));
            exit();

        }

    }

    //delete ministry;
    public function removeMinistry($overseerid,$ministryid){


        $sql = "DELETE FROM ministry WHERE overseerid = :overseerid AND id = :ministryid";

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":overseerid", $overseerid);
            $stmt->bindParam(":ministryid", $ministryid);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                return true;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR no ministry deleted ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "message"=> $message ));
            exit();

        }

    }

    //edit ministry;

    public function editMinistry($property,$value,$ministryid){


        $sql = "UPDATE ministry SET ".$property." = :value WHERE id = :ministryid";

        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt->bindParam(":value", $value);
            $stmt->bindParam(":ministryid", $ministryid);
            $stmt -> execute();
            $rowscount = $stmt -> rowCount();

            if($rowscount > 0){
                return true;
            }else{
                return FALSE;
            }


        }
        catch(PDOException $e){
            $message = 'SQL ERROR no ministry edited ' . $e -> getMessage();
            $results = "0";
            echo json_encode(array("results"=> $results, "message"=> $message ));
            exit();

        }

    }
}
//end of class;
?>