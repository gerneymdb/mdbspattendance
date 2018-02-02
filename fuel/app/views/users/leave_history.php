<?php
    $base_url = \Fuel\Core\Config::get("base_url");
?>
<section class="container-fluid" id="leave_history">
    <div class="row">
        <div class="col-md-12">
            <h3 class="text-center">
                <?php
                    $yr = (empty($year) || $year == null || $year == 0 || (!is_numeric($year)))? "" : $year;
                    echo  $yr . " ".$lang["leave records"];
                ?>
            </h3>
            <table class="table table-responsive">
                <?php if(count($leave_history) > 0):?>
                    <thead>
<!--                        <tr>-->
<!--                            <th colspan="9"><p class="text-center">--><?php //echo $year . " Leave Records" ?><!--</p></th>-->
<!--                        </tr>-->
                        <tr class="thead">
                            <th><?php echo $lang["leave_label"] ?></th>
                            <th><?php echo $lang["request_date"]?></th>
                            <th><?php echo $lang["from"] ?></th>
                            <th><?php echo $lang["to"] ?></th>
                            <th><?php echo $lang["reason"] ?></th>
                            <th><?php echo $lang["status"] ?></th>
                            <th><?php echo $lang["attachment"] ?></th>
                            <th><?php echo $lang["approved_by"]?></th>
                            <th><?php echo $lang["comments"] ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($leave_history as $leave):?>
                            <tr>
                                <td><?php echo $leave->type ?></td>
                                <td><?php echo $leave->date_filed ?></td>
                                <td><?php echo $leave->start_leave ?></td>
                                <td><?php echo $leave->end_leave ?></td>
                                <td><?php echo $leave->reason ?></td>
                                <td><?php echo $leave->status ?></td>
                                <td>
<!--                                    --><?php //if(!empty($leave->attachments) || $leave->attachments != ""): ?>
                                        <a href="<?php echo $base_url . 'files/leave/' . $leave->attachments ?>">
                                            <i class="fa fa-file-image-o"></i>
                                        </a>
<!--                                    --><?php //endif; ?>
                                </td>
                                <td><?php echo $leave->approved_by ?></td>
                                <td><?php echo $leave->comments ?></td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                <?php else:?>
                    <div class="alert alert-info">
                        <p class="text-center"><i class='glyphicon glyphicon-exclamation-sign'></i> <strong>No Record Found</strong></p>
                    </div>
                <?php endif;?>


        </div>
    </div>
</section>