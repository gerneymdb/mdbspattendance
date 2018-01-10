<?php
    $base_url = \Fuel\Core\Config::get("base_url");
    $page = \Fuel\Core\Session::get_flash("page");

    $year = strftime("%Y", time());
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
        <li class = "<?php echo ($page == 'attendance')? 'active' : '' ?>"><a href="<?php echo $base_url ."administrator/manage_attendance"?>">Manage Attendance <i class="fa fa-calendar pull-right"></i></a></li>
        <li class = "<?php echo ($page == 'employees')? 'active' : '' ?>"><a href="<?php echo $base_url ."administrator/manage_employees"?>">Manage Employee <i class="fa fa-users pull-right"></i></a></li>
        <li class = "<?php echo ($page == 'holidays')? 'active' : '' ?>"><a href="<?php echo $base_url ."administrator/holidays/".$year ?>">Holidays <i class="fa fa-calendar-times-o pull-right"></i></a></li>
        <li class = "<?php echo ($page == 'leave applications')? 'active' : '' ?>"><a href="<?php echo $base_url ."administrator/leave_application"?>">Leave Applications <i class="fa fa-wpforms pull-right"></i></a></li>
        <li class = "<?php echo ($page == 'work schedule')? 'active' : '' ?>"><a href="<?php echo $base_url ."administrator/work_schedule"?>">Work Schedule <i class="fa fa-tasks pull-right"></i></a></li>
        <li class = "<?php echo ($page == 'system settings')? 'active' : '' ?>"><a href="<?php echo $base_url ."administrator/system_settings"?>">System Settings <i class="fa fa-cog pull-right"></i></a></li>
        <li class = "<?php echo ($page == 'new admin')? 'active' : '' ?>"><a href="<?php echo $base_url ."administrator/new_admin"?>">New Administrator <i class="fa fa-user-secret pull-right"></i></a></li>
    </ul>
</section>
<section class="admin-content">
    <div class="admin-top-menu">
        <ul class="clearfix">
            <li><a href="<?php echo $base_url ."login/logout"?>">logout <i class="fa fa-sign-out"></i></a></li>
            <li><a href="#">hello <?php echo \Auth\Auth::get_profile_fields('fname');?> <i class="fa fa-user-secret"></i></a></li>
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