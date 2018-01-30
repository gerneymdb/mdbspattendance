$(document).ready(function(){

    // reject button
    var $btn_rjct  = $(".leave-app .status-btn-reject");
    var $no_rjct   = $("#no_reject");
    var $yes_rjct  = $("#yes_reject");
    var $rjct_form = $("#reject_form");
    var input_r = $("#reject_form input[name='leave_id']");
    var text_r  = $("#reject_form textarea[name='comment']");

    // clicking reject button
    $btn_rjct.on("click", function(){
        var $this = $(this);

        // get leave id
        var leave_id = $this.attr("data-leave");

        input_r.val(leave_id)

        // open modal
        $("#reject_modal").modal({
            keyboard: false
        });
    });

    // clicking no on reject modal
    $no_rjct.on("click", function(){
        input_r.val("");
    });

    // ajax call change status of a leave submitted by the employees
    $yes_rjct.on("click", function(){
        var url = $("#reject_url").val();

        var formdata = $("#reject_form").serialize();

        var modal_content = $("#reject_modal").find(".modal-content");
        var loader = modal_content.find(".loader");

        var notification = $("#reject_modal").find(".notification_msg");

        $.ajax({
            type: "post",
            url: url,
            data:formdata,
            dataType: "JSON",
            async: true,
            beforeSend: function(){
                // display loader
                var loader = $(".loader");
                loader.removeClass("hide")
            },
            success: function (response) {

                input_r.val("");
                text_r.val("");

                var $status = $("#"+response["leave_id"]+" .status-holder .status");



                $status.removeClass("status-approved");
                $status.addClass("status-rejected");
                $status.text("rejected");

                var loader = $(".loader");
                loader.addClass("hide");

                $("#reject_modal").modal("hide");


            },
            error: function(response){
                var info = response.responseText.split(":");

                loader.addClass("hide");
                notification.find("p.message_title").addClass("text-danger").text(info[1]);
                notification.find("p.message_content").text(info[0]);
                notification.removeClass("hide");
            }
        });


    });

    // close notification
    var cls_mdl_reject = $("#reject_modal").find(".close_info");
    cls_mdl_reject.on("click", function(){
        var $this = $(this);
        $this.parent().addClass("hide");
        $("#reject_modal").modal("hide");
    });


    // approve button
    var $btn_apprv = $(".leave-app .status-btn-approve");
    var $no_apprv  = $("#no_approve");
    var $yes_apprv = $("#yes_approve");
    var input_a = $("#approve_form input[name='leave_id']");
    var text_a  = $("#approve_form textarea[name='comment']");

    $btn_apprv.on("click", function(){
        var $this = $(this);

        // get leave id
        var leave_id = $this.attr("data-leave");

        input_a.val(leave_id);

        //open modal
        $("#approve_modal").modal({
            keyboard: false
        });
    });

    $no_apprv.on("click", function(){
        input_a.val("");
    });


    // ajax call change status of a leave submitted by the employees
    $yes_apprv.on("click", function(){
        // url for updating status
        var url = $("#approve_url").val();
        // fetch data in the form
        var formdata = $("#approve_form").serialize();

        var modal_content = $("#approve_modal").find(".modal-content");
        var loader = modal_content.find(".loader");

        var notification = $("#approve_modal").find(".notification_msg");


        // peform ajax request
        $.ajax({
            type: "post",
            url: url,
            data:formdata,
            dataType: "JSON",
            async: true,
            beforeSend: function(){
                // display loader
                var loader = $(".loader");
                loader.removeClass("hide")
            },
            success: function (response) {

                input_a.val("");
                text_a.val("");

                var $status = $("#"+response["leave_id"]+" .status-holder .status");

               $status.removeClass("status-rejected");
               $status.addClass("status-approved");
               $status.text("approved");

               var loader = $(".loader");
               loader.addClass("hide");

               // hide modal
               $("#approve_modal").modal("hide");

            },
            error: function(response){

                var info = response.responseText.split(":");

                loader.addClass("hide");
                notification.find("p.message_title").addClass("text-danger").text(info[1]);
                notification.find("p.message_content").text(info[0]);
                notification.removeClass("hide");

            }
        });
    });

    // close notification
    var cls_mdl_dlte_shift = $("#approve_modal").find(".close_info");
    cls_mdl_dlte_shift.on("click", function(){
        var $this = $(this);
        $this.parent().addClass("hide");
        $("#approve_modal").modal("hide");
    });

    if($(".leave-app").length === 1){
        var comments = $(".leave-app").find(".comment-icon");

        comments.on("click", function(){
            var $this = $(this);
            var id = $this.attr("id");

            $(".comments-"+id).toggleClass("hide");
        });
    }

});// document.ready