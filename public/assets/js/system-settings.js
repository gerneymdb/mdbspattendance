$(document).ready(function(){
    if($(".system_settings").length === 1){


        // edit leave category and delete
        var edit_leave_btn    = $(".edit-leave-cat");
        var delete_leave_btn  = $(".delete-leave-cat");


        edit_leave_btn.on("click", function(){
            var $this = $(this);
            var leave_id     = $this.attr("data-leave-id");
            var leave_name   = $this.attr("data-leave-name");
            var days_alloted = $this.attr("data-days-alloted");

            $("#lv_sett_id").val(leave_id);
            $("#leave_name").val(leave_name);
            $("#daysalloted").val(days_alloted);

            $("#edit_leave").modal({
                keyboard: false
            });

        });

        var edt_save = $("#save_changes");
        edt_save.on("click", function(){

            var $this = $(this);

            var url = $("#leave-cat-set-url").val();
            var formdata = $("#leave_cat_form").serialize();

            var modal_content = $("#edit_leave").find(".modal-content");
            var loader = modal_content.find(".loader");

            var notification = $("#edit_leave").find(".notification_msg");

            $.ajax({
                type: "post",
                url: url,
                data:formdata,
                dataType: "JSON",
                async: true,
                beforeSend: function(){
                    // display loader
                    loader.removeClass("hide")
                },
                success: function (response) {
                    if(typeof response === "object"){

                        // row id
                        var row = $("#"+response['leave_settings_id']);
                        // update leavename
                        row.find(".leave_cat_name").text(response['leave_name']);
                        // update days alloted
                        row.find(".leave_cat_days").text(response['days_alloted']);
                        // update edit button
                        row.find(".edit-leave-cat").attr("data-leave-name", response['leave_name']);
                        row.find(".edit-leave-cat").attr("data-days-alloted", response['days_alloted']);

                        loader.addClass("hide");
                        notification.find("p.message_title").addClass("text-success").text(response['title']);
                        notification.find("p.message_content").text(response["message"]);
                        notification.removeClass("hide");

                    }
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
        var cls_mdl_btn_leave = $("#edit_leave").find(".close_info");
        cls_mdl_btn_leave.on("click", function(){
            var $this = $(this);
            $this.parent().addClass("hide");
            $("#edit_leave").modal("hide");
        });



        delete_leave_btn.on("click", function(){
            var $this = $(this);
            // get leave id
            var leave_id   = $this.attr("data-leave-id");
            // get leave name
            var leave_name = $this.attr("data-leave-name");

            // leave cat input
            var leave_cat_name = $('#leave_cat_name');
            // hidden input for
            var leave_id_input = $("#dlte_lv_sett_id");

            // populate
            leave_cat_name.val(leave_name);
            leave_id_input.val(leave_id);

            // show delete modal
            $("#delete_leave").modal({
                keyboard: false
            });
        });


        var delete_leave_cat = $('#delete_leave_cat');
        delete_leave_cat.on("click", function(){

            var $this = $(this);

            var url = $("#dlte_leave_cat_set_url").val();
            var formdata = $("#dlte_lve_form").serialize();

            var modal_content = $("#delete_leave").find(".modal-content");
            var loader = modal_content.find(".loader");

            var notification = $("#delete_leave").find(".notification_msg");

            $.ajax({
                type: "post",
                url: url,
                data:formdata,
                dataType: "JSON",
                async: true,
                beforeSend: function(){
                    // display loader
                    loader.removeClass("hide")
                },
                success: function (response) {

                    console.log(response);

                    if(typeof response === "object"){

                        // row id
                        var row = $("#"+response['leave_settings_id']);
                        // remove leave category
                        row.remove();

                        loader.addClass("hide");
                        notification.find("p.message_title").addClass("text-success").text(response['title']);
                        notification.find("p.message_content").text(response["message"]);
                        notification.removeClass("hide");

                    }
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
        var cls_mdl_btn_delete = $("#delete_leave").find(".close_info");
        cls_mdl_btn_delete.on("click", function(){
            var $this = $(this);
            $this.parent().addClass("hide");
            $("#delete_leave").modal("hide");
        });

    }
});