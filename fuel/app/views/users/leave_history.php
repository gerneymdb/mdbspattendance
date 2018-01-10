<?php
    $base_url = \Fuel\Core\Config::get("base_url");
?>
<section class="container-fluid" id="leave_history">
    <div class="row">
        <div class="col-md-12">

            <table class="table table-responsive">
                <?php if(count($leave_history) > 0):?>
                    <thead>
                        <tr>
                            <th colspan="9"><p class="text-center"><?php echo $year . " Leave Records" ?></p></th>
                        </tr>
                        <tr class="thead">
                            <th>Leave</th>
                            <th>Requested On</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Attachment</th>
                            <th>Approved By</th>
                            <th>Comments</th>
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
                <?php else:?>
                    <thead>
                        <tr>
                            <th colspan="1"><p class="text-center"><?php echo $year . " Leave Records" ?></p></th>
                        </tr>
                        <tr>
                            <td>No Leave Records Found.!</td>
                        </tr>
                    </thead>
                <?php endif;?>
            </table>

        </div>
    </div>
</section>