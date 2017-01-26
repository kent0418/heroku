<?php
    $host = $_SERVER["HTTP_HOST"];
    $xsrf = md5($id);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <meta name="xsrf" content="<?php echo $xsrf; ?>">
        <meta property="og:url" content="http://<?php echo $_SERVER["HTTP_HOST"]."/".$_SERVER["REQUEST_URI"]; ?>" />
        <meta property="og:title" content="<?php echo ucfirst($id); ?>" />
        <meta property="og:description" content="" />
        <meta property="og:type" content="music.song" />
        <title><?php echo ucfirst($id); ?></title>
    </head>
</html>
