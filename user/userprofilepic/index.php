<?php
require_once $_SERVER["DOCUMENT_ROOT"]. "/api/user/user.class.php";

$destination = $_SERVER["DOCUMENT_ROOT"].'/api/images/users/'.time().$_FILES['userprofilepic']['name'];
$imageurl = "/api/images/users/".time().$_FILES['userprofilepic']['name'];

if($results = move_uploaded_file($_FILES['userprofilepic']['tmp_name'],$destination)){
    $user = new user();
    if(!isset($_SESSION)){
        session_start();
    }

    $sql = 'SELECT profilepic FROM user WHERE user.id = '.$_SESSION["userid"];

    try{
        $stmt = $user->db -> prepare($sql);
        $stmt -> execute();
        $user_profilepic = $stmt -> fetch()[0];
    }
    catch(PDOException $e){
        $error = $e -> getMessage();
        return false;
    }
    if(count($user_profilepic)>0){
        if(file_exists($_SERVER["DOCUMENT_ROOT"].$user_profilepic)){
            unlink($_SERVER["DOCUMENT_ROOT"].$user_profilepic);
        }
    }

    if($results = $user->editUser($_SESSION["userid"],"profilepic", $imageurl)){
        $message = 'file uploaded '. $_SESSION["userid"];
    }else{
        $message = 'file not uploaded';
    }

}else{
    $results = 0;
    $message = 'no upload'. $_FILES['userprofilepic']['name'];
}

echo json_encode(array('results'=>$results, "message"=>$message));
exit;
?>