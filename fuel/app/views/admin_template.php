<?php
use Fuel\Core\Lang as lang;
use Fuel\Core\Cookie as cookie;

$base_url = \Fuel\Core\Config::get("base_url");
$page = \Fuel\Core\Session::get_flash("page");

$year = strftime("%Y", time());

// check if there are lang cookies set
$language = cookie::get("lang");
if(empty(trim($language))){
    // lang cookie is not set, set to default
    lang::set_lang("en", true);

}else{
    // lang cookie is set, use it to change the language
    lang::set_lang($language, true);
}

lang::load("admin_menu");
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php echo \Fuel\Core\Asset::css("bootstrap.min.css");?>
    <?php echo \Fuel\Core\Asset::css("font-awesome.css")?>
    <?php echo \Fuel\Core\Asset::css("admin-base.css"); ?>
    <?php echo \Fuel\Core\Asset::css("admin-menu.css"); ?>
    <?php echo \Fuel\Core\Asset::css("admin-system-settings.css"); ?>
    <?php echo \Fuel\Core\Asset::css("admin-work-schedule.css"); ?>
    <?php echo \Fuel\Core\Asset::css("admin-holidays.css"); ?>
    <?php echo \Fuel\Core\Asset::css("admin-attendance.css"); ?>
    <?php echo \Fuel\Core\Asset::css("admin-leave-application.css"); ?>
    <?php echo \Fuel\Core\Asset::css("admin-manage-employees.css"); ?>
    <?php echo \Fuel\Core\Asset::css("jquery-ui.css"); ?>
    <?php echo \Fuel\Core\Asset::css("jquery-ui.multidatespicker.css"); ?>
    <title><?php echo isset($title) ? $title : "" ?></title>
</head>
<body>
<section class="admin-side-menu">
    <ul>
        <li class = "<?php echo ($page == 'attendance')? 'active' : '' ?>"><a href="<?php echo $base_url ."administrator/manage_attendance"?>"><?php echo __("manage attendance") ?> <i class="fa fa-calendar pull-right"></i></a></li>
        <li class = "<?php echo ($page == 'employees')? 'active' : '' ?>"><a href="<?php echo $base_url ."administrator/manage_employees"?>"><?php echo __("manage employee") ?> <i class="fa fa-users pull-right"></i></a></li>
        <li class = "<?php echo ($page == 'holidays')? 'active' : '' ?>"><a href="<?php echo $base_url ."administrator/holidays/".$year ?>"><?php echo __("holidays") ?> <i class="fa fa-calendar-times-o pull-right"></i></a></li>
        <li class = "<?php echo ($page == 'leave applications')? 'active' : '' ?>"><a href="<?php echo $base_url ."administrator/leave_application"?>"><?php echo __("leave applications") ?> <i class="fa fa-wpforms pull-right"></i></a></li>
        <li class = "<?php echo ($page == 'work schedule')? 'active' : '' ?>"><a href="<?php echo $base_url ."administrator/work_schedule"?>"><?php echo __("work schedule") ?> <i class="fa fa-tasks pull-right"></i></a></li>
        <li class = "<?php echo ($page == 'system settings')? 'active' : '' ?>"><a href="<?php echo $base_url ."administrator/system_settings"?>"><?php echo __("system settings") ?> <i class="fa fa-cog pull-right"></i></a></li>
        <li class = "<?php echo ($page == 'new admin')? 'active' : '' ?>"><a href="<?php echo $base_url ."administrator/new_admin"?>"><?php echo __("new administrator") ?> <i class="fa fa-user-secret pull-right"></i></a></li>
    </ul>
</section>
<section class="admin-content">
    <div class="admin-top-menu clearfix">

        <?php echo \Fuel\Core\Form::open(array('action'=>'login'.DIRECTORY_SEPARATOR.'changeLanguage', 'class'=>'navbar-form navbar-left  admin-user-menu', 'method'=>'post', 'id'=>''))?>
        <!--                --><?php //echo \Fuel\Core\Form::csrf()?>
        <div class="form-group">
            <label for="lang"><?php echo __("select lang")?></label>
            <select name="lang" id="lang" class="form-control language-select">
                <?php
                    $lang_list = [
                        "en"    => "English",
                        "ja"    => "Japanese",
                        "ta"    => "Tagalog",
                        "ilo"   => "Ilonggo"
                    ];

                    if(!empty($language)){
                        echo "<option value='".$language."' selected>".$lang_list[$language]."</option>";
                    }
                ?>
                <option value="en">English</option>
                <option value="ja">Japanese</option>
                <option value="ta">Tagalog</option>
                <option value="ilo">Ilonggo</option>
            </select>
        </div>
        <button type="submit" class="btn btn-default btn-translate"><?php echo __("translate")?></button>
        <?php echo \Fuel\Core\Form::close()?>

        <ul class="clearfix">
            <li><a href="<?php echo $base_url ."login/logout"?>"><?php echo __("logout") ?> <i class="fa fa-sign-out"></i></a></li>
            <li><a href="#"><?php echo __("hello")?> <?php echo \Auth\Auth::get_profile_fields('fname');?> <i class="fa fa-user-secret"></i></a></li>
        </ul>
    </div>
    <?php echo (isset($content)? $content : "no-content")?>
</section>

<!--  scripts  -->
<?php echo \Fuel\Core\Asset::js("jquery-3.2.1.min.js");?>
<?php echo \Fuel\Core\Asset::js("jquery-ui.min.js");?>
<?php echo \Fuel\Core\Asset::js("bootstrap.min.js");?>
<?php echo \Fuel\Core\Asset::js("jquery-ui.multidatespicker.js");?>
<?php echo \Fuel\Core\Asset::js("attendance.js");?>
<?php echo \Fuel\Core\Asset::js("employees.js");?>
<?php echo \Fuel\Core\Asset::js("holidays.js");?>
<?php echo \Fuel\Core\Asset::js("leave-application.js");?>
<?php echo \Fuel\Core\Asset::js("system-settings.js");?>
<?php echo \Fuel\Core\Asset::js("work-schedule.js");?>
</body>
</html>