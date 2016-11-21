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
        <meta property="og:url" content="<?php echo "http://".$host."/".$id; ?>.html" />
        <meta property="og:title" content="<?php echo ucfirst($id); ?>" />
        <meta property="og:description" content="" />
        <title><?php echo ucfirst($id); ?></title>
    </head>
</html>