<?php
    use Fuel\Core\Lang as lang;
    use Fuel\Core\Cookie as cookie;

    $base_url = \Fuel\Core\Config::get("base_url");

    // check if there are lang cookies set
    $language = cookie::get("lang");
    if(empty(trim($language))){
        // lang cookie is not set, set to default
        lang::set_lang("en", true);

    }else{
        // lang cookie is set, use it to change the language
        lang::set_lang($language, true);
    }

    lang::load("menu");
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php echo \Fuel\Core\Asset::css("bootstrap.min.css");?>
<!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">-->
    <?php echo \Fuel\Core\Asset::css("font-awesome.css")?>
    <?php echo \Fuel\Core\Asset::css("attendance.css");?>
    <?php echo \Fuel\Core\Asset::css("jquery-ui.css");?>
    <?php echo \Fuel\Core\Asset::css("jquery-ui.theme.min.css");?>
<!--    --><?php //echo \Fuel\Core\Asset::css("jquery-ui.multidatespicker.css");?>
    <title><?php echo isset($title) ? $title : "" ?></title>
</head>
<body>
<section class="container-fluid">
    <div class="row">
        <div class="col-md-12 main-nav">
            <nav class="navbar navbar-inverse" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-container">
                        <span class="sr-only">Show and Hide the Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>

                <div class="collapse navbar-collapse" id="navbar-container">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="<?php echo $base_url ."user/attendance"?>"><?php echo lang::get("attendance")?></a></li>
                        <li><a href="<?php echo $base_url ."user/history/" . strftime("%Y", time())?>"><?php echo lang::get("attendance_history")?></a></li>
                        <li><a href="<?php echo $base_url ."user/leave_application"?>"><?php echo lang::get("leave_application")?></a></li>
                        <li><a href="<?php echo $base_url ."user/leave_history/" . strftime("%Y", time()) ?>"><?php echo lang::get("leave_history") ?></a></li>
                        <li><a href="<?php echo $base_url ."login/logout"?>"><?php echo lang::get("logout") ?></a></li>
                        <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"></a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</section>
<?php echo isset($content) ? $content : "No Content"; ?>

<!--  scripts  -->
<?php echo \Fuel\Core\Asset::js("jquery-3.2.1.min.js");?>
<?php echo \Fuel\Core\Asset::js("bootstrap.min.js");?>
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>-->
<?php echo \Fuel\Core\Asset::js("jquery-ui.min.js");?>
<?php //echo \Fuel\Core\Asset::js("jquery-ui.multidatespicker.js");?>
<?php echo \Fuel\Core\Asset::js("user.js");?>
</body>
</html>