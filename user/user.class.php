<?php
include_once $_SERVER['DOCUMENT_ROOT']. '/api/includes/db/connect2.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/api/includes/helpers/encodepassword.php';

class user{
public $db;


  public function __construct(){
     $dbh = new Dbconn();
     $this -> db = $dbh->dbcon;
  }

//update user last activity;
public function  updateUserLastActivity(){
    session_start();
    if(isset($_SESSION["userid"])){
        $id = $_SESSION["userid"];
        $email = $_SESSION["email"];
        $property = "lastactivity";
        $value = time();
        $this->editUser($id,$email,$property,$value);
        return TRUE;
    }
return FALSE;
}


//get active users;
    public function getActiveUsers($time,$interval,$amtperpage,$pgn){
        $sql = '(SELECT user.id,firstname,surname,profilepic,role.name as rolename
	FROM user INNER JOIN role ON user.roleid = role.id WHERE ('.$time.' - lastactivity ) <= :interval LIMIT '.$pgn.','.$amtperpage.')';
        try{
            $stmt = $this -> db -> prepare($sql);

            $stmt -> bindParam(":interval", $interval);
            $stmt -> execute();
            $users = $stmt -> fetchAll();
            return $users;
        }
        catch(PDOException $e){
            $error = 'SQL error '. $e -> getMessage();
            return FALSE;
        }
//end of catch;
    }
//end of getactive user;

  public function getUser($id){
    $sql = '(SELECT user.id,firstname,surname,email,password,mobile,
	 dateofregistration,profilepic, roleid, functionid,about,rolenote,role.name AS rolename,
	 user.stateid,user.LGAid, state.name as state, LGA.name as lga,
	 public,gender,lastactivity
	FROM user INNER JOIN role ON user.roleid = role.id INNER JOIN state ON
	user.stateid = state.id INNER JOIN LGA ON user.LGAid = LGA.id
    WHERE user.id = :id)';

try{
    $stmt = $this -> db -> prepare($sql);

    $stmt -> bindParam(":id", $id);
    $stmt -> execute();

    $user = $stmt -> fetch();
}
 catch(PDOException $e){
    $error = $e -> getMessage();
    return FALSE;
}
//end of catch;
  if(count($user)>0){
     $this -> user = $user;
     return $user;
  }else{
       return FALSE;
   }
  }
//end of getUSER;
//get users products;

//end of getUsersA

//end of getUsersArticles;



public function updateUserPushId($pushid, $email){
    $sql = "UPDATE user SET pushid = :pushid WHERE email = :email";
    try{
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":pushid", $pushid);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return true;
    }catch (PDOException $e){
        $result = "0";
        $message = /*$e->getMessage().*/" SQL ERROR: unable to do push update";
        echo json_encode(array("results"=>$result, "message"=>$message));
        exit;
    }
}


//end of getUsersArticles;
    public function updateUserPermanentPushId( $email){
        $sql = "UPDATE user SET permanentpushid = pushid WHERE email = :email";
        try{
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            return true;
        }catch (PDOException $e){
            $result = "0";
            $message = /*$e->getMessage().*/" SQL ERROR: unable to do push update";
            echo json_encode(array("results"=>$result, "message"=>$message));
            exit;
        }
    }

public function isLoggedIn(){

if(isset($GLOBALS["ApiInput"]['login'])){
   if($user = $this->isInDatabase($GLOBALS["ApiInput"]['email'], $GLOBALS["ApiInput"]['password'])){

     if(!isset($_SESSION)){
         session_start();
     }

     if(isset($GLOBALS["ApiInput"]['pushid'])){
         $this->updateUserPushId($GLOBALS["ApiInput"]['pushid'], $GLOBALS["ApiInput"]['email']);
         $_SESSION["pushid"] = $GLOBALS["ApiInput"]['pushid'];
     }

     $_SESSION['userdata']= $user;
     $_SESSION['userid']= $user[0];
     $_SESSION['email']= $GLOBALS["ApiInput"]['email'];
     $_SESSION['password']= $GLOBALS["ApiInput"]['password'];
     session_write_close();
     return $user;
   }else {

       if(!isset($_SESSION)){
           session_start();
       }
       unset($_SESSION['userid']);
       unset($_SESSION['email']);
       unset($_SESSION['password']);
       unset($_SESSION['userdata']);
       session_write_close();
      return FALSE;
   }
 }

if(isset($GLOBALS["ApiInput"]['logout'])){
        if(!isset($_SESSION)){
            session_start();
        }
       $this->updateUserPushId(NULL, $_SESSION['email']);
       unset($_SESSION['userdata']);
       unset($_SESSION['userid']);
       unset($_SESSION['facebookid']);
       unset($_SESSION['email']);
       unset($_SESSION['password']);

       return FALSE;
 }


if(!isset($_SESSION)){
  session_start();
}
if(isset($_SESSION['password'])){
    if($user = $this->isInDatabase($_SESSION['email'], $_SESSION['password'])){
        //if pushid is provided update pushid for user;
        if(isset($_SESSION['pushid']) || isset($GLOBALS["ApiInput"]['pushid'])){
            if(isset($GLOBALS["ApiInput"]['pushid'])){
                $pushid = $GLOBALS["ApiInput"]['pushid'];
            }else{
                $pushid = $_SESSION["pushid"];
            }

            $this->updateUserPushId($pushid, $_SESSION['email']);
        }

        $_SESSION['userdata']= $user;
        $_SESSION['userid']= $user[0];
        return $user;
    }else {
        unset($_SESSION['userid']);
        unset($_SESSION['email']);
        unset($_SESSION['password']);
        unset($_SESSION['userdata']);
        return false;
    }

}

//facebook login;
if(isset($_SESSION['facebookid'])){
    if($user = $this->isFacebookidInDatabase($_SESSION['facebookid'])){
        $_SESSION['userdata']= $user;
        $_SESSION['userid']= $user[0];
        return $user;
    }else {
        unset($_SESSION['userid']);
        unset($_SESSION['email']);
        unset($_SESSION['password']);
        unset($_SESSION['userdata']);
        return FALSE;
    }
}


//login from a recovered password mail;
if(isset($_GET['xpxwn'])){
   if($user = $this->isInDatabase($_GET['email'], $_GET['xpxwn'])){
     $_SESSION['userdata']= $user;
     $_SESSION['userid']= $user[0];
     $_SESSION['email']= $_GET['email'];
     $_SESSION['password']= $_GET['xpxwn'];
    return TRUE;
   }else {
       unset($_SESSION['userid']);
       unset($_SESSION['email']);
       unset($_SESSION['password']);
       unset($_SESSION['userdata']);
       return FALSE;
   }
 }
return false;
//end of isindatabase;
}
//end of login;

public  function isInDatabase($email,$password){

        if((isset($GLOBALS["ApiInput"]["LoginFromLocalStorage"])) || (isset($_GET['xpxwn']))){
            $password2 = $password;
        }else{
            $password2 = encodePassword($password);
        }


        $sql = '(SELECT user.id,email,password,firstname,surname,mobile,stateid,lgaid,public,profilepic,roleid
        ,functionid, role.name as rolename FROM user INNER JOIN role ON user.roleid = role.id
    	WHERE email = :email AND password = :password)';
        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt -> bindParam(":password", $password2);
            $stmt -> bindParam(":email", $email);

            $stmt -> execute();
            $userdata = $stmt -> fetch();
        }
        catch(PDOException $e){
            $error =  $e -> getMessage();
            return false;
        }
        if($userdata[0] > 0){
            return $userdata;
        }else{
            return false;
        }

    }

//is facebookid in database;

public  function isFacebookidInDatabase($facebookid){

        $sql = '(SELECT user.id,email,password,firstname,surname,mobile,stateid,lgaid,public,profilepic
        ,role.name as rolename FROM user INNER JOIN role ON user.roleid = role.id
    	WHERE facebookid = :facebookid)';
        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt -> bindParam(":facebookid", $facebookid);

            $stmt -> execute();
            $userdata = $stmt -> fetch();
        }
        catch(PDOException $e){
            $error = 'unable to get your facebook account '. $e -> getMessage();
            return FALSE;
        }
        if($userdata[0] > 0){
            return $userdata;
        }else{
            return FALSE;
        }

    }



public function registerProUser($firstname, $surname,$email, $password,
$mobile, $gender, $dateofbirth, $dateofregistration, $about,
$stateid,$lgaid, $profilepic,$roleid,$rolenote,$public){
   $sql = "INSERT INTO user
        (firstname , surname , email ,
5	 password , mobile, gender,
	 dateofbirth , dateofregistration ,
	 about ,stateid,lgaid,
	profilepic,roleid,rolenote,public)
	VALUES(:firstname, :surname,
	:email, :password ,
	:mobile, :gender,:dateofbirth ,
	:dateofregistration,  :about, :stateid,:lgaid,
	:profilepic, :roleid, :rolenote, :public )";

  try{
    $db = $this -> db;
    $stmt =  $db-> prepare($sql);
    $stmt -> bindParam(":firstname", $firstname);
    $stmt -> bindParam(":surname", $surname);
    $stmt -> bindParam(":email", $email);
    $stmt -> bindParam(":password", $password);
    $stmt -> bindParam(":mobile", $mobile);
    $stmt -> bindParam(":gender", $gender);
    $stmt -> bindParam(":dateofbirth", $dateofbirth);
    $stmt -> bindParam(":dateofregistration", $dateofregistration);
    $stmt -> bindParam(":about", $about);
    $stmt -> bindParam(":stateid", $stateid);
    $stmt -> bindParam(":lgaid", $lgaid);
    $stmt -> bindParam(":profilepic", $profilepic);
    $stmt -> bindParam(":roleid", $roleid);
    $stmt -> bindParam(":rolenote", $rolenote);
    $stmt -> bindParam(":public", $public);

    $stmt -> execute();
  }
   catch(PDOException $e){
     $error = 'Email Or Mobile Already In Use Try Another '.$e -> getMessage();
     return FALSE;
   }


    return TRUE;
}
//register user end;
public function registerUser($firstname, $surname,$email, $password,
                                 $mobile, $gender, $dateofbirth, $dateofregistration, $about,
                                 $stateid,$lgaid,$roleid,$rolenote,$public){
        $sql = 'INSERT INTO user
        (firstname , surname , email ,
	 password , mobile, gender,
	 dateofbirth , dateofregistration ,
	 about ,stateid,lgaid,roleid,rolenote,public)
	VALUES(:firstname, :surname,
	:email, :password ,
	:mobile, :gender,:dateofbirth ,
	:dateofregistration,  :about, :stateid,:lgaid,:roleid, :rolenote, :public )';

        try{
            $db = $this -> db;
            $stmt =  $db -> prepare($sql);
            $stmt -> bindParam(":firstname", $firstname);
            $stmt -> bindParam(":surname", $surname);
            $stmt -> bindParam(":email", $email);
            $stmt -> bindParam(":password", $password);
            $stmt -> bindParam(":mobile", $mobile);
            $stmt -> bindParam(":gender", $gender);
            $stmt -> bindParam(":dateofbirth", $dateofbirth);
            $stmt -> bindParam(":dateofregistration", $dateofregistration);
            $stmt -> bindParam(":about", $about);
            $stmt -> bindParam(":stateid", $stateid);
            $stmt -> bindParam(":lgaid", $lgaid);
            $stmt -> bindParam(":roleid", $roleid);
            $stmt -> bindParam(":rolenote", $rolenote);
            $stmt -> bindParam(":public", $public);

            $stmt -> execute();
            $userid = $this-> db ->lastInsertId();
        }
        catch(PDOException $e){
            $error = 'Email  Already In Use Try Another ' .$e -> getMessage();
            return FALSE;
        }

    //update push id;
        if(!isset($_SESSION)){
            session_start();
        }
        if(isset($GLOBALS["ApiInput"]['pushid'])){
            $this->updateUserPushId($GLOBALS["ApiInput"]['pushid'], $GLOBALS["ApiInput"]['email']);
            $_SESSION["pushid"] = $GLOBALS["ApiInput"]['pushid'];
        }

        return array("id"=>$userid, "email"=>$email, "password"=>$password, "firstname"=>$firstname, "surname"=>$surname,
            "mobile"=>$mobile, "state"=>$stateid, "lga"=>$lgaid, "public"=>$public, "profilepic"=>NULL);
    }

//register user end;

public function editUser($userid, $property, $value){
   $sql = 'UPDATE user SET '.$property. ' = :value
	        WHERE id = :userid';
 try{
    $stmt = $this -> db -> prepare($sql);
    $stmt -> bindParam(":value", $value);
    $stmt -> bindParam(":userid", $userid);

    $stmt -> execute();
    $rowscount = $stmt -> rowCount();
  }
   catch(PDOException $e){
     $error = 'SQL ERROR UNABLE TO EDIT USER '. $e -> getMessage();
     return FALSE;
   }
if($rowscount > 0){
   return TRUE;
 }
 else{
   return FALSE;
 }
}
//end of edituser;

//password;
//passwordrecovery Must BE INCLUDED IN INDEX DIRECTLY CALLED;
//stops at sending mail to user and setting URL FOR LOCATION HEADER;

public function recoverPassword(){
if(isset($input['recoverpassword'])){
  $email=$input['email'];

   $sql = 'SELECT user.id , password FROM user
		WHERE email = :email';
try{
 $stmt = $this->db->prepare($sql);
 $stmt -> bindParam(":email", $email);

 $stmt -> execute();
 $row =   $stmt -> fetch();
  }
  catch(PDOException $e){
     $error = 'SQL ERROR UNABLE TO RESET PASSWORD '.$e -> getMessage();
     return FALSE;
   }
  if($row[0] > 0){
     $userpwd = $row["password"];
     $userid = $row["id"];

   $url = 'http://'.$_SERVER['SERVER_NAME'].
'/bona/user/index.php?email='.$email.'&xixdn='
.$userid.'&xpxwn='.$userpwd;

   $header= '"From:support@ugo.com"."/r/n"';
   $to=$input['email'];
   $msg= '<p> CLICK HERE TO LOG IN AND RESET YOUR PASSWORD</p>  '.$url;
   $subject = 'Your Recovery Link';

//mail user the login url with changed password;
mail($to,$subject,$msg,$header);

    $output ='A Mail Has Been Sent To Your EMAIL NOW';
    $url = "Location: /user/index.php?uid=".$userid."&output=".$output;
    header($url);
    exit();
    }
    else{
        $error = 'This Email Is NOT REGISTERED WITH US.';
    	include $_SERVER['DOCUMENT_ROOT']. '/bona/user/forms/recoverpassword.html.php';
        exit();
    }    
  }

}
// end of recoverpassword end;

//reset password;

public function resetPassword($id, $email, $oldpassword,$newpassword){
  $password = encodePassword($newpassword);
  $password2 = encodePassword($oldpassword);

$sql='UPDATE user SET
	password = :password WHERE email = :email AND password = :password2';
try{
$stmt = $this -> db -> prepare($sql);
   $stmt -> bindParam(":password", $newpassword);
   $stmt -> bindParam(":email", $email);
   $stmt -> bindParam(":password2", $oldpassword);

$stmt -> execute();
$rowscount = $stmt -> rowCount();
   }
    catch(PDOException $e){
     $error = 'SQL ERROR UNABLE MATCH PASSWORD '. $e -> getMessage();
     return FALSE;
     }
if($rowscount > 0){
    return TRUE;
  }else{
      return FALSE;
     }


}
//END RESET PASSWORD;

//get ward users by lga;
    public function getUsersInLGAByRole($LGAid,$roleid){
        $sql = '(SELECT user.id, firstname, surname,
	   profilepic,role.name as rolename,LGA.name as LGAname
	   FROM user INNER JOIN LGA ON user.LGAid = LGA.id
	   INNER JOIN role ON user.roleid = role.id
	   WHERE (user.LGAid = :LGAid AND role.id = :roleid))';
        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt -> bindParam(":LGAid", $LGAid);
            $stmt -> bindParam(":roleid", $roleid);

            $stmt -> execute();
            $rowscount = $stmt -> rowCount();
            if($rowscount > 0){
                $users = $stmt -> fetchAll();
                return $users;
            }else{
                return FALSE;
            }
        }
        catch(PDOException $e){
            $error = 'SQL ERROR UNABLE TO GET USEES IN LGA ' .$e -> getMessage();
            return FALSE;

        }
    }
//end of get users in a state;

//get users by state by role;
    public function getUsersInStateByRole($stateid,$roleid){
        $sql = '(SELECT user.id, firstname, surname,
	   profilepic,role.name as rolename,state.name as statename
	   FROM user INNER JOIN state ON user.stateid = state.id
	   INNER JOIN role ON user.roleid = role.id
	   WHERE (user.stateid = :stateid AND role.id = :roleid))';
        try{
            $stmt = $this -> db -> prepare($sql);
            $stmt -> bindParam(":stateid", $stateid);
            $stmt -> bindParam(":roleid", $roleid);

            $stmt -> execute();
            $rowscount = $stmt -> rowCount();
            if($rowscount > 0){
                $users = $stmt -> fetchAll();
                return $users;
            }else{
                return FALSE;
            }
        }
        catch(PDOException $e){
            $error = 'SQL ERROR UNABLE TO GET  state persons in the role '.$e -> getMessage();
            return FALSE;

        }
    }
//end of get users in a STATE;
//get user roles;

//get wards;
    public function getRoles(){
        $sql = '(SELECT id, name FROM role)';

        try{

            $stmt = $this -> db -> prepare($sql);

            $stmt -> execute();
            $rowscount = $stmt -> rowCount();
            if($rowscount > 0){
                $roles = $stmt -> fetchAll();
                return $roles;
            }else{
                return FALSE;
            }
        }
        catch(PDOException $e){
            $error = 'SQL ERROR UNABLE TO GET roles '.$e -> getMessage();
            return FALSE;

        }
    }
//end of get roles
}
//end of user class;


?>