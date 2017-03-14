<?php
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://blogger.report/play.jpg');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $image = curl_exec($ch);
    curl_close($ch);
    @ob_end_clean();
    @ob_end_flush();
    header("Content-Type:image/jpeg");
    echo $image;
?>

