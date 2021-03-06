<?php  $base_url = \Fuel\Core\Config::get("base_url"); ?>
<section class="container-fluid">

    <div class="row">

        <div class="col-md-6 col-md-offset-3">

            <?php
            $msg = \Fuel\Core\Session::get_flash("a_msg");
            $smsg = \Fuel\Core\Session::get_flash("a_smsg");
            ?>

            <?php if(is_array($msg) && count($msg) > 0 ):?>

                <?php
                    foreach ($msg as $error) {
                        echo "<div class=\"alert alert-danger text-center\"><p class='text-center'><i class=\"fa fa-warning\"></i>  ".$error."</p></div>";
                    }
                ?>

            <?php endif;?>

            <?php if(is_array($smsg) && count($smsg) > 0 ):?>

                <?php
                    foreach ($smsg as $error) {
                        echo "<div class=\"alert alert-success text-center\"><p class='text-center'><i class=\"fa fa-warning\"></i>  ".$error."</p></div>";
                    }
                ?>

            <?php endif;?>

            <h3><?php echo __("Add a new administrator") ?></h3>
            <form action="<?php echo $base_url .'administrator/add_admin'?>" method="post">

                <?php echo \Fuel\Core\Form::csrf()?>

                <div class="form-group">
                    <label for="username"><?php echo __("username") ?></label>
                    <input type="text" name="username" placeholder="<?php echo __("administrator's username") ?>" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="username"><?php echo __("Email") ?></label>
                    <input type="email" name="email" placeholder="<?php echo __("administrator's email") ?>" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="fname"><?php echo __("First Name") ?></label>
                    <input type="text" name="fname" id="fname" placeholder="<?php echo __("admin's first name") ?>" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="mname"><?php echo __("Middle Name") ?></label>
                    <input type="text" name="mname" id="mname" placeholder="<?php echo __("admin's middle name") ?>" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="fname"><?php echo __("Last Name") ?></label>
                    <input type="text" name="lname" id="lname" placeholder="<?php echo __("admin's last name") ?>" class="form-control"/>
                </div>

                <button type="submit" class="btn btn-default pull-right"><i class="fa fa-plus-square-o"></i> <?php echo __("Add") ?></button>

            </form>

        </div>

    </div>

</section>