<?php
$link = mysqli_connect($_SERVER['SERVER_NAME'], 'root');
if(!$link){
  $error = 'unable to connect to mysql';
  include $_SERVER['DOCUMENT_ROOT'].'/bona/includes/errors/error.html.php';
  exit();
  }

if(!mysqli_select_db($link, 'biblealarm')){
  $error = 'unable to select database';
  include $_SERVER['DOCUMENT_ROOT'].'/bona/includes/errors/error.html.php';
  exit();
 }
?>