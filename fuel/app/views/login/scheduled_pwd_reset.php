

<section class="container-fluid first-login">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">

            <div class="firstLogin-form">

                <?php echo \Fuel\Core\Form::open(array("action"=>"login/changePassword", "method"=>"post", "class"=>"form-signin", "id"=>"login-form"))?>
                <h2 class="form-signin-heading"><?php echo $lang["form_heading"]?></h2>
                <hr />
                <?php

                $msg = \Fuel\Core\Session::get_flash("msg");

                if(is_array($msg) && count($msg) > 0 ){

                    foreach ($msg as $error) {
                        echo "<div class=\"alert alert-danger text-center\"><i class='glyphicon glyphicon-exclamation-sign'></i>  ".$error."</div>";
                    }

                }

                $smsg = \Fuel\Core\Session::get_flash("smsg");

                if(is_array($smsg) && count($smsg) > 0 ){

                    foreach ($smsg as $msg) {
                        echo "<div class=\"alert alert-success text-center\"><i class='glyphicon glyphicon-exclamation-sign'></i>  ".$msg."</div>";
                    }

                }

                ?>
                <?php echo \Fuel\Core\Form::csrf(); ?>
                <input type="hidden" value="<?php echo \Fuel\Core\Session::get("username") != null ? \Fuel\Core\Session::get("username") : ""; ?>" name="username" />
                <div class="form-group input-group">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-lock"></span>
                        </span>
                    <input type="password" class="form-control" name="old_password" placeholder="<?php echo $lang["old_pwd"]?>" required />
                </div>
                <div class="form-group input-group">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-lock"></span>
                        </span>
                    <input type="password" class="form-control" name="new_password" placeholder="<?php echo $lang["new_pwd"]?>" required />
                </div>

                <div class="form-group input-group">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-lock"></span>
                        </span>
                    <input type="password" class="form-control" name="confirm_password" placeholder="<?php echo $lang["confirm_pwd"]?>" required />
                </div>
                <hr />
                <div class="form-group">
                    <button type="submit" name="btn-signin" class="btn btn-default" value="submit" id="firstLogin-btn">
                        <i class="glyphicon glyphicon-floppy-disk"></i> <?php echo $lang["save_btn"]?>
                    </button>
                </div>

                <?php echo \Fuel\Core\Form::close() ?>

            </div>

        </div>
    </div>
</section>