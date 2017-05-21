<?php
	$ip = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	$browser = @$_SERVER['HTTP_USER_AGENT'];
	$request = @$_SERVER['REQUEST_URI'];
	$referer = @$_SERVER['HTTP_REFERER'];
	$app = explode('.'@$_SERVER['HTTP_HOST'])[0];

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://$app.vulu.info$request");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"X-Forwarded-for: $ip",
		"User-Agent: $browser",
		"Referer: $referer",
		"Via: Compression"
    ));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	$output = curl_exec($ch);
	curl_close($ch);
	echo $output;
?>
