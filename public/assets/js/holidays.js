$(document).ready(function(){

    // set start holiday date picker
    $("#set_start_day").datepicker({
        dateFormat: "yy-mm-dd"
    });
    // set end holiday date picker
    $("#set_end_day").datepicker({
        dateFormat: "yy-mm-dd"
    });

    // start holiday date picker
    $("#start_day").datepicker({
        dateFormat: "yy-mm-dd"
    });
    // end holiday date picker
    $("#end_day").datepicker({
        dateFormat: "yy-mm-dd"
    });


    // edit button
    if($(".outer-div .inner-div .edit").length > 0){
        var $edit = $(".outer-div .inner-div .edit");

        $edit.on("click", function(){
            var $this = $(this);
            var $holiday_id  = $this.attr("data-holiday-id");
            var holiday_info = $("#"+$holiday_id);

            // get holiday info
            var holiday_title = holiday_info.find("span.holiday-title");
            var from = $("#"+$holiday_id+" i.holiday-start").attr('data-holiday-start');
            var to = $("#"+$holiday_id+" i.holiday-end").attr('data-holiday-end');
            var type = $("#"+$holiday_id+" span.holiday-type").attr('data-holiday-type');
            var holiday_description = $("#"+$holiday_id+" span.holiday-description").text();
            var with_work = $("#"+$holiday_id+" span.holiday-with-work").attr('data-holiday-with-work');

            // put holiday value into the form
            $('#edit_form input[name="holiday_id"]').val($holiday_id);
            $("#holiday_names").val(holiday_title.attr('data-holiday-name'));
            $("#start_day").val(from);
            $("#end_day").val(to);

            // remove if there is currently prepended option element
            $("#type").find("option[selected]").remove();
            // prepend the new one
            $("#type").prepend("<option value='"+type+"' selected>"+type+"</option>");

            $("#Edescription").text(holiday_description);
            var wWork = "";
            if(with_work === "1"){
                wWork = "With Work"
            }else{
                wWork = "No Work"
            }
            $("#with_work").prepend("<option value='"+with_work+"' selected>"+wWork+"</option>");

            //show modal
            $("#edit").modal({
                keyboard: false
            })
        });

    }

    // save edit
    if($("#save_edit").length == 1){


        var save_btn = $("#save_edit");

        save_btn.on("click", function(){
            var url = $("#holiday_update_url").val();

            var formdata = $("#edit_form").serialize();

            var modal_content = $("#edit").find(".modal-content");
            var loader = modal_content.find(".loader");

            var notification = $("#edit").find(".notification_msg");

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

                    if(typeof response === "object"){
                        // locate div element with this id
                        var row = $("#"+response['holiday_id']);
                        // inside that div element find the elements that has info of the holiday
                        row.find(".holiday-title").text(response['holiday_name']);
                        row.find(".holiday-start").text("From: "+format_date(response['start_day']));
                        row.find(".holiday-end").text("To: "+format_date(response['end_day']));
                        row.find(".holiday-type").text(response['type']);
                        row.find(".holiday-description").text(response['description']);
                        console.log(response['with_work']);
                        var classes   = response['with_work'] == 1 ? "fa fa-hourglass" : "fa fa-hourglass-o";
                        var work      = response['with_work'] == 1 ? "With Work" : "No Work";
                        var with_work = "<i class='"+classes+"'> "+work+"</i>";
                        // replace the info of that element
                        row.find(".holiday-with-work").html(with_work);

                        // replace the data
                        row.find(".holiday-title").attr("data-holiday-name", response['holiday_name']);
                        row.find(".holiday-start").attr("data-holiday-start", response['start_day']);
                        row.find(".holiday-end").attr("data-holiday-end", response['end_day']);
                        row.find(".holiday-type").attr("data-holiday-type", response['type']);
                        row.find(".holiday-with-work").attr("data-holiday-with-work", response['with_work']);

                        var loader = $(".loader");
                        loader.addClass("hide");
                        $("#edit").modal("hide");
                    }

                },
                error: function(response){

                    loader.addClass("hide");
                    notification.find("p.message_title").addClass("text-danger").text("error");
                    notification.find("p.message_content").text(response.responseText);
                    notification.removeClass("hide");

                }
            });
        });

    }

    // close notification
    var cls_mdl_btn_edit = $("#edit").find(".close_info");
    cls_mdl_btn_edit.on("click", function(){
        var $this = $(this);
        $this.parent().addClass("hide");
    });

    // delete
    if($(".outer-div .inner-div .delete").length > 0){
        var $delete = $(".outer-div .inner-div .delete")

        $delete.on("click", function(){
            var $this = $(this);
            var $holiday_id = $this.attr("data-holiday-id");
            var $delete_holiday = $("#delete_holiday");

            //clear first any element currently inside
            $("#delete_form").html("");

            $("#"+$holiday_id+" .holiday-info").clone().appendTo("#delete_form");

            $("#delete_form").append("<input type='hidden' name='holiday_id' id='id_holiday' value='"+$holiday_id+"' />");


           $("#delete").modal({
               keyboard: false
           });
        });
    }

    if($("#delete_holiday").length == 1){
        var $delete_btn = $("#delete_holiday");

        $delete_btn.on("click", function(){
            var url = $("#holiday_delete_url").val();

            var formdata = $("#delete_form").serialize();
            var holiday_id = $("#id_holiday").val();

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

                        var loader = $(".loader");
                        loader.addClass("hide");

                        $("#"+holiday_id).remove();

                        $("#delete").modal("hide");
                    }
                },
                error: function(){

                }
            });

        });

    }
    format_date("a");
});
// this f
function format_date(date = null){
    if(date !== null){
        var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        var givenDate = date.split("-");
        return months[givenDate[1]-1]+"-"+givenDate[2]+"-"+givenDate[0];
    }else{
        return null;
    }
}
