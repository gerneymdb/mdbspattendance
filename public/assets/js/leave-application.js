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

        $.ajax({
            type: "post",
            url: url,
            data:formdata,
            async: true,
            beforeSend: function(){
                // display loader
                var loader = $(".loader");
                loader.removeClass("hide")
            },
            success: function (response) {
                if(response !== "0"){
                    input_r.val("");
                    text_r.val("");

                    var $status = $("#"+response+" .status-holder .status");

                    setTimeout(function(){

                        $status.removeClass("status-approved");
                        $status.addClass("status-rejected");
                        $status.text("rejected");

                        var loader = $(".loader");
                        loader.addClass("hide");

                        $("#reject_modal").modal("hide");
                    }, 3000);
                }
            },
            error: function(){

            }
        });


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
        // peform ajax request
        $.ajax({
            type: "post",
            url: url,
            data:formdata,
            async: true,
            beforeSend: function(){
                // display loader
                var loader = $(".loader");
                loader.removeClass("hide")
            },
            success: function (response) {
                if(response !== "0"){
                    input_a.val("");
                    text_a.val("");

                    var $status = $("#"+response+" .status-holder .status");

                    //remove loader
                   setTimeout(function(){

                       $status.removeClass("status-rejected");
                       $status.addClass("status-approved");
                       $status.text("approved");

                       var loader = $(".loader");
                       loader.addClass("hide");

                       // hide modal
                       $("#approve_modal").modal("hide");
                   }, 3000);


                }
            },
            error: function(){

            }
        });
    });

});// document.ready