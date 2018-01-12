<?php  $base_url = \Fuel\Core\Config::get("base_url"); ?>
<section class="container-fluid leave-app">
    <div class="row">
        <div class="col-md-8 col-md-offset-2 pix">
            <h2>leave applications</h2>
            <?php if(count($leaves) > 0):?>

                <?php foreach ($leaves as $info):?>

                    <div class="box clearfix">
                        <div class="color" id="<?php echo $info->leave_id?>">
                            <div class="btn-group status-holder">
                                <?php
                                    $status = [
                                        "pending" => "status-pending",
                                        "approved" => "status-approved",
                                        "rejected" => "status-rejected"
                                    ];
                                ?>
                                <p>Status: <span class="status <?php echo $status[$info->status];?>"><?php echo $info->status?></span></p>
                            </div>
                            <div class="btn-group action-approve">
                                <button type="button" class="btn btn-danger status-btn-approve" data-leave="<?php echo $info->leave_id ?>">Approved</button>
                            </div>
                            <div class="btn-group action-reject">
                                <button type="button" class="btn btn-success status-btn-reject" data-leave="<?php echo $info->leave_id ?>" >Reject</button>
                            </div>
                        </div>
                        <div class = "groups">
                            <div class="text">
                                <p><?php echo $employees[$info->userid] ?></p>
                                <p><?php echo $info->type ?></p>
                            </div>
                            <div class="rand">
                                <p>
                                    <span class="five">
                                        <?php
                                            $start = strtotime($info->start_leave);
                                            $end   = strtotime($info->end_leave);

                                            echo ceil((($end - $start) / 86400));
                                        ?>
                                    </span>
                                    <span>Days</span>
                                    <span class="frm-form">From:</span>
                                    <span><?php echo $info->start_leave ?></span>
                                    <span class="t-bold">To:</span>
                                    <span><?php echo $info->end_leave ?></span>
                                    <span class="filed">Date Filed:</span>
                                    <span><?php echo $info->date_filed ?></span>
                                </p>
                            </div>
                            <div class="son">
                                <p><span class="rea">Reason:</span> <?php echo $info->reason ?></p>
                            </div>
                            <div class="attach">
                                <p>
                                    <?php
                                        if(!empty($info->attachments)){
                                            echo "Attachments: <a href='".$base_url."files/leave/".$info->attachments."'><span class=\"glyphicon glyphicon-paperclip\" aria-hidden=\"true\"></span></a>";
                                        }
                                    ?>
                                </p>
                            </div>
                            <div class="comment">
                                <p id="<?php echo $info->leave_id ?>">
                                    <?php
                                        if(!empty($info->comments)){
                                            echo "Comments:<i class=\"fa fa-commenting-o\" aria-hidden=\"true\"></i>";
                                        }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>

                <?php endforeach;?>

            <?php endif;?>


        </div>
    </div>
</section>

<!--Reject Modal-->
<div class="modal fade" id="reject_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="loader hide"><span class="spin-holder"><i class="fa fa-spinner fa-pulse"></i> processing</span></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Reject this leave application?</h4>
            </div>
            <div class="modal-body">
                <form action="#" id="reject_form">
                    <p>Why are you going to reject this?</p>
                    <input type="hidden" name="leave_id" value="" />
                    <input type="hidden" value="<?php echo $base_url . 'ajaxcall/reject'?>" id="reject_url">
                    <textarea name="comment" class="form-control" rows="5"></textarea>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="no_reject">No</button>
                <button type="button" class="btn btn-primary" id="yes_reject">Yes</button>
            </div>
        </div>
    </div>
</div>

<!--Approved-->
<div class="modal fade" id="approve_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="loader hide"><span class="spin-holder"><i class="fa fa-spinner fa-pulse"></i> processing</span></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Approve this leave application?</h4>
            </div>
            <div class="modal-body">
                <form action="#" id="approve_form">
                    <p>Add comment?</p>
                    <input type="hidden" name="leave_id" value="" />
                    <input type="hidden" value="<?php echo $base_url . 'ajaxcall/approve'?>" id="approve_url">
                    <textarea name="comment" class="form-control" rows="5"></textarea>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="no_approve">No</button>
                <button type="button" class="btn btn-primary" id="yes_approve">Yes</button>
            </div>
        </div>
    </div>
</div>