<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php echo \Fuel\Core\Asset::css("reset.css");?>
    <?php echo \Fuel\Core\Asset::css("bootstrap.min.css");?>
    <?php echo \Fuel\Core\Asset::css("font-awesome.min.css");?>
    <?php echo \Fuel\Core\Asset::css("messages.css");?>
    <title><?php echo isset($title) ? $title : "" ?></title>
</head>
<body>
<?php echo isset($content) ? $content : "No Content"; ?>
<?php echo \Fuel\Core\Asset::js("jquery-3.2.1.min.js");?>
<?php echo \Fuel\Core\Asset::js("bootstrap.min.js");?>
<?php echo \Fuel\Core\Asset::js("login.js");?>
</body>
</html>