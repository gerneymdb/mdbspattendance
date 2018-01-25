<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 language-container">
            <?php echo \Fuel\Core\Form::open(array('action'=>'login'.DIRECTORY_SEPARATOR.'changeLanguage', 'class'=>'form-inline', 'method'=>'post', 'id'=>''))?>
<!--                --><?php //echo \Fuel\Core\Form::csrf()?>
                <div class="form-group">
                    <label for="lang"><?php echo $lang_form["select_lang"]?></label>
                    <select name="lang" id="lang" class="form-control">
                        <option value="en">English</option>
                        <option value="ja">Japanese</option>
                        <option value="ta">Tagalog</option>
                        <option value="ilo">Ilonggo</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-default"><?php echo $lang_form["translate"]?></button>
            <?php echo \Fuel\Core\Form::close()?>
        </div>
    </div>
</div>

<div class="signin-form">

    <div class="container">
        <?php echo \Fuel\Core\Form::open(array('action'=>'login'.DIRECTORY_SEPARATOR.'employeeLogin', 'class'=>'form-signin', 'method'=>'post', 'id'=>'login-form'))?>
            <?php echo \Fuel\Core\Form::csrf(); ?>

            <h2 class="form-signin-heading"><?php echo $lang["form_heading"]?></h2>
            <hr />
            <?php

                $msg = \Fuel\Core\Session::get_flash("msg");
                if(is_array($msg) && count($msg) > 0 ){

                    foreach ($msg as $error) {
                        echo "<div class=\"alert alert-danger text-center\"><i class='glyphicon glyphicon-exclamation-sign'></i>  ".$error."</div>";
                    }
                }

            ?>
            <div class="form-group input-group">
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-user"></span>
                </span>
                <input type="text" class="form-control" name="userid" placeholder="<?php echo $lang["id_placeholder"]?>"  autofocus/>
            </div>

            <div class="form-group input-group">
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-lock"></span>
                </span>
                <input type="password" class="form-control" name="password" placeholder="<?php echo $lang["password_placeholder"]?>" />
            </div>
            <hr />
            <div class="form-group">
                <button type="submit" name="btn-login" class="btn btn-default">
                    <i class="glyphicon glyphicon-log-in"></i> <?php echo $lang["submit_btn"]?></button>
            </div>
        <?php echo \Fuel\Core\Form::close(); time() ?>

    </div>

</div>