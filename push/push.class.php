<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/api/includes/db/connect2.php';

class push{


private $singleID = '';
private $fcmserverkey = "AAAAln_fqmA:APA91bGH-wERCh6i-f-EOW8sOYij_xGoksAhGOJj8twHXl2ttvfUTS_l4HMbEWudry8sTYO5gXk0JLdu5GdRdGM_6L7cNYHTez5h_YyscZWwGgqnJrREKcqDwmythVC5I6ANelkx-EMC";
private $db;

    public function __construct(){
        $dbh = new Dbconn();
        $this -> db = $dbh->dbcon;
    }

// https://firebase.google.com/docs/cloud-messaging/http-server-ref#notification-payload-support
    public function sendPush($regids, $message, $title, $data){
        $fcmMsg = array(
            'body' => $message,
            'title' => $title,
            'sound' => "default",
            'color' => "#203E78"
        );

        $fcmFields = array(
            'to' => $regids,
            'priority' => 'high',
            'notification' => $fcmMsg,
            'data' => $data,
        );

        $fcmFields = json_encode($fcmFields);
        $headers = array(
            'Authorization: key=' .$this->fcmserverkey,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fcmFields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);

        if($httpcode >= 200 && $httpcode < 300){
            return true;
        }else{
            return false;
        }

    }


}
//end of class
?>