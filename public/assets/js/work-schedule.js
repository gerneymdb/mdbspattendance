$(document).ready(function(){
    if($(".work-schedule").length === 1){
        //==============================================================================
        var w_label   = $("label.w_label");
        var work_days = $("#working_days");
        var w_days    = [];

        w_label.on("click", function(){
            var $this = $(this);
            // get the day-input info
            var d_i =  $this.attr("data-input");
            var input = work_days.find("input[data-day='"+d_i+"']");

            setTimeout(function(){
                if(input.is(":checked")){
                    var doff_input = $("#dayoff-days").find("input[data-day='"+d_i+"']");
                    doff_input.parent().hide();
                }else{
                    var doff_input = $("#dayoff-days").find("input[data-day='"+d_i+"']");
                    doff_input.parent().show();
                }
            }, 100);

            w_days.length = 0;
            setTimeout(function(){
                work_days.find("input[name='w_days[]']:checked").each(function(){
                    w_days.push($(this).val());
                });
                var wdays = w_days.join();
                $("#work_days_input").val(wdays);
            }, 100);

        });

        var d_label  = $("label.d_label");
        var day_offs = $("#dayoff-days");
        var d_days   = []

        d_label.on("click", function(){
            var $this = $(this);
            // get the day-input info
            var d_i =  $this.attr("data-input");
            var input = day_offs.find("input[data-day='"+d_i+"']");

            setTimeout(function(){
                if(input.is(":checked")){
                    var wday_input = $("#working_days").find("input[data-day='"+d_i+"']");
                    wday_input.parent().hide();
                }else{
                    var wday_input = $("#working_days").find("input[data-day='"+d_i+"']");
                    wday_input.parent().show();
                }
            }, 100);


            d_days.length = 0;
            setTimeout(function(){
                day_offs.find("input[name='d_days']:checked").each(function(){
                    d_days.push($(this).val());
                });
                var ddays = d_days.join();
                $("#day_off_input").val(ddays);
            }, 100);
        });
        //=================================================================================================



        var btn_edit_shift = $(".btn-edit-shift");
        btn_edit_shift.on("click", function(){
            var $this = $(this);

            var shiftid   = $("#edit_shift_id");
            var shiftname = $("#eshiftname");
            var workdays  = $("#workdays");
            var dayoff    = $("#dayoff");

            var sedit_hr  = $("#sedit_hr");
            var sedit_min = $("#sedit_min");
            var sedit_sec = $("#sedit_sec");

            var eedit_hr  = $("#eedit_hr");
            var eedit_min = $("#eedit_min");
            var eedit_sec = $("#eedit_sec");

            // populate inputs
            shiftid.val($this.attr("data-shift-id"));
            shiftname.val($this.attr("data-shift-name"));
            workdays.val($this.attr("data-work-days"));
            dayoff.val($this.attr("data-dayoff-days"));

            // remove prepended option[selected]
            sedit_hr.find("option[selected]").remove();
            sedit_min.find("option[selected]").remove();
            sedit_sec.find("option[selected]").remove();

            eedit_hr.find("option[selected]").remove();
            eedit_min.find("option[selected]").remove();
            eedit_sec.find("option[selected]").remove();

            // prepend default values
            var start_shift = $this.attr("data-start-shift").split(":");
            var end_shift   = $this.attr("data-end-shift").split(":");

            sedit_hr.prepend("<option value='"+start_shift[0]+"' selected>"+start_shift[0]+"</option>");
            sedit_min.prepend("<option value='"+start_shift[1]+"' selected>"+start_shift[1]+"</option>");
            sedit_sec.prepend("<option value='"+start_shift[2]+"' selected>"+start_shift[2]+"</option>");
            //
            eedit_hr.prepend("<option value='"+end_shift[0]+"' selected>"+end_shift[0]+"</option>");
            eedit_min.prepend("<option value='"+end_shift[1]+"' selected>"+end_shift[1]+"</option>");
            eedit_sec.prepend("<option value='"+end_shift[2]+"' selected>"+end_shift[2]+"</option>");

            //toggle modal
            $("#edit_shift_modal").modal({
                keyboard: false
            });
        });

        var btn_save_btn = $("#btn-save-shift");
        btn_save_btn.on("click", function(){

            var $this = $(this);

            var url = $("#update-shift-url").val();
            var formdata = $("#save-shift-form").serialize();

            var modal_content = $("#edit_shift_modal").find(".modal-content");
            var loader = modal_content.find(".loader");

            var notification = $("#edit_shift_modal").find(".notification_msg");

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
                        var row = $("#"+response['shift_id']);
                        // update info
                        row.find(".shift_name").text(response['shift_name']);
                        row.find(".work_days").text(response['work_days']);
                        row.find(".day_off").text(response['day_off']);
                        row.find(".start_shift").text(response['start_shift']);
                        row.find(".end_shift").text(response['end_shift']);

                        var btn_edit = row.find(".btn-edit-shift");
                        btn_edit.attr("data-shift-name", response['shift_name']);
                        btn_edit.attr("data-work-days", response['work_days']);
                        btn_edit.attr("data-dayoff-days", response['day_off']);
                        btn_edit.attr("data-start-shift", response['start_shift']);
                        btn_edit.attr("data-end-shift", response['end_shift']);

                        var btn_delete = row.find(".btn-delete-shift");
                        btn_delete.attr("data-shift-name", response['shift_name']);

                        loader.addClass("hide");
                        notification.find("p.message_title").addClass("text-success").text("Success");
                        notification.find("p.message_content").text("Information has been change");
                        notification.removeClass("hide");

                    }
                },
                error: function(response){

                    console.log(response);

                    loader.addClass("hide");
                    notification.find("p.message_title").addClass("text-danger").text("error");
                    notification.find("p.message_content").text(response.responseText);
                    notification.removeClass("hide");

                }
            });

        });
        // close notification
        var cls_mdl_edit_shift = $("#edit_shift_modal").find(".close_info");
        cls_mdl_edit_shift.on("click", function(){
            var $this = $(this);
            $this.parent().addClass("hide");
            $("#edit_shift_modal").modal("hide");
        });

        // delete shift
        var btn_delete_shift = $(".btn-delete-shift");
        btn_delete_shift.on("click", function(){
            var $this = $(this);
            var shift_id = $this.attr("data-shift-id");
            var shift_name = $this.attr("data-shift-name");

            // gather inputs
            var delete_shift_id = $("#delete_shift_id");
            var dshiftname      = $("#dshiftname");

            //populate the inputs
            delete_shift_id.val(shift_id);
            dshiftname.val(shift_name);

            $('#delete_shift_modal').modal({
                keyboard: false
            });
        });
        var btn_delete_shifts = $("#btn-delete-shift");
        btn_delete_shifts.on("click", function(){

            var $this = $(this);

            var url = $("#delete-shift-url").val();
            var formdata = $("#delete-shift-form").serialize();

            var modal_content = $("#delete_shift_modal").find(".modal-content");
            var loader = modal_content.find(".loader");

            var notification = $("#delete_shift_modal").find(".notification_msg");

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
                        var row = $("#"+response['shift_id']);
                        // delete info
                        row.remove();

                        loader.addClass("hide");
                        notification.find("p.message_title").addClass("text-success").text("Success");
                        notification.find("p.message_content").text("Shift record successfully deleted.");
                        notification.removeClass("hide");

                    }
                },
                error: function(response){

                    console.log(response);

                    loader.addClass("hide");
                    notification.find("p.message_title").addClass("text-danger").text("error");
                    notification.find("p.message_content").text(response.responseText);
                    notification.removeClass("hide");

                }
            });

        });
        // close notification
        var cls_mdl_dlte_shift = $("#delete_shift_modal").find(".close_info");
        cls_mdl_dlte_shift.on("click", function(){
            var $this = $(this);
            $this.parent().addClass("hide");
            $("#delete_shift_modal").modal("hide");
        });

    }
});