<?php
	error_reporting(E_ALL);
ini_set("display_errors", 1);
	if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) || !isset($_GET["uid"])){
		$_SERVER['HTTP_IF_MODIFIED_SINCE'] = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : "";
	  	header('Last-Modified: '.$_SERVER['HTTP_IF_MODIFIED_SINCE'],true,304);
	  	exit;
	}
	require_once('SimpleImage.php');
	$img = new abeautifulsite\SimpleImage("https://graph.facebook.com/".$_GET["uid"]."/picture?width=200&height=200");
	$img->fit_to_height(200);
	$img->pixelate(rand(8,15));
	$img->overlay('playbutton.png', 'center center', 1, 0, 0);
	ob_start();
	$img->output();
	$content = ob_get_contents();
	ob_end_clean();
	header("Cache-Control:public, max-age=0");
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() - (60*60*24*45)) . ' GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time() + (60*60*24*45)) . ' GMT');
	header("Content-Type:image/jpg");
	echo $content;
?>
