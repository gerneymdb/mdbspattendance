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
            var $holiday_id = $this.attr("data-holiday-id");

            // get holiday info
            var holiday_title = $("#"+$holiday_id+" span.holiday-title").attr('data-holiday-name');
            var from = $("#"+$holiday_id+" i.holiday-start").attr('data-holiday-start');
            var to = $("#"+$holiday_id+" i.holiday-end").attr('data-holiday-end');
            var type = $("#"+$holiday_id+" span.holiday-type").attr('data-holiday-type');
            var holiday_description = $("#"+$holiday_id+" span.holiday-description").text();
            var with_work = $("#"+$holiday_id+" span.holiday-with-work").attr('data-holiday-with-work');



            // put holiday value into the form
            $('#edit_form input[name="holiday_id"]').val($holiday_id);
            $("#holiday_name").val(holiday_title);
            $("#start_day").val(from);
            $("#end_day").val(to);
            $("#type").prepend("<option value='"+type+"' selected>"+type+"</option>");
            $("#Hdescription").text(holiday_description);
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

                        setTimeout(function(){

                            var loader = $(".loader");
                            loader.addClass("hide");

                            $("#edit").modal("hide");
                        }, 1000);
                    }
                },
                error: function(){

                }
            });
        });


    }


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

            console.log(holiday_id);

            console.log(formdata);
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

                        setTimeout(function(){

                            var loader = $(".loader");
                            loader.addClass("hide");

                            $("#"+holiday_id).remove();

                            $("#delete").modal("hide");
                        }, 1000);
                    }
                },
                error: function(){

                }
            });

        });

    }
});
