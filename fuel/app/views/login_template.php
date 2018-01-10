<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php echo \Fuel\Core\Asset::css("reset.css");?>
    <?php echo \Fuel\Core\Asset::css("bootstrap.min.css");?>
    <?php echo \Fuel\Core\Asset::css("login.css");?>
    <?php echo \Fuel\Core\Asset::css("pwdReset.css");?>
    <title><?php echo isset($title) ? $title : "Login" ?></title>
</head>
<body>
<?php echo isset($content) ? $content : "No Content"; ?>
<?php echo \Fuel\Core\Asset::js("jquery-3.2.1.min.js");?>
<?php echo \Fuel\Core\Asset::js("bootstrap.min.js");?>
<?php echo \Fuel\Core\Asset::js("login.js");?>
</body>
</html>
