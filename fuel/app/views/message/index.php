<section class="container-fluid msg-container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <?php $msg = \Fuel\Core\Session::get_flash("msg")?>
            <?php echo isset($msg) ? $msg : ""?>
            <?php $smsg = \Fuel\Core\Session::get_flash("smsg")?>
            <?php echo isset($smsg) ? $smsg : ""?>

            <?php $location = \Fuel\Core\Session::get_flash("location")?>
            <a href="<?php echo $location ?>" class="btn btn-default">Proceed <i class="fa fa-arrow-right"></i></a>
        </div>
    </div>
</section>