$(document).ready(function(){

    if($(".employees").length > 0){

        var rst_pwd = $(".btn_rst_pwd");

        // ajax call change status of a leave submitted by the employees
        rst_pwd.on("click", function(){

            // this reset buton
            var $this = $(this);
            // the url to submit to
            var url = $this.attr("data-url");
            // this specific container
            var container = $this.closest(".employee-info");
            // this specific form
            var form = container.find(".reset_pwd");
            // the data in the form
            var formdata = form.serialize();
            // this container's loader
            var loader = container.find(".loader");
            // this new password container
            var pwd_holder = container.find(".new_pwd_container");
            // new password p tag
            var pwd_txt = pwd_holder.find(".new_pwd");

            // reset password
            $.ajax({
                type: "post",
                url: url,
                data:formdata,
                async: true,
                beforeSend: function(){
                    // display loader
                    loader.removeClass("hide")
                },
                success: function (response) {
                    if(response !== "0"){

                        setTimeout(function(){

                            console.log(response);
                            loader.addClass("hide");

                            pwd_txt.text(response);
                            pwd_holder.addClass("show");

                        }, 3000);
                    }
                },
                error: function(){

                }
            });

        });

        var hide_pwd  = $(".employees .new_pwd_container .hide_new_pwd");

        hide_pwd.on("click", function(){
            var $this = $(this);
            $this.parent().removeClass("show");
        });


        // edit
        var emp_edit = $(".employees .emp_edit");

        emp_edit.on("click", function(){
            var $this = $(this);
            // this edit button's parent container
            var $this_container = $this.closest(".employee-info");
            // fullname
            var fullname = $this_container.find(".record .info .emp_fullname");

            // break down of employees full name
            var fname = fullname.find(".fname");
            var mname = fullname.find(".mname");
            var lname = fullname.find(".lname");

            //info
            var info = $this_container.find(".record .info");
            // birthdate and civil status
            var civil_status = info.find(".civil_status .cstatus");
            var birthdate = info.find(".birthdate .birth_date");

            var info_position = $this_container.find(".record .work-schedule");
            // company position
            var co_position = info_position.find(".co_position .position");

            // the email address of the employee
            var email = $this_container.find(".record .work-schedule");
            var e_address = email.find(".emp_email .email");

            // the shift id and shift name of the employess
            var work_sched = $this_container.find(".record .work-schedule");
            var shift_val  = work_sched.find("p[data-shift-id]");
            var shift_name = work_sched.find("p[data-shift-id] span.shift-name");

            // the option element to be appended to the select element
            var $shift = "<option value='"+shift_val.attr('data-shift-id')+"' selected class='custom_shift'>"+shift_name.text()+"</option>";

            // apply the value to the input field.
            $("#first_name").val(fname.text());
            $("#middle_name").val(mname.text());
            $("#last_name").val(lname.text());
            $("#email_address").val(e_address.text());
            $("#user_id").val($this.attr("data-userid"));
            $("#employeeid").val($this.attr("data-employeeid"));
            $("#bday").val(birthdate.text());
            $("#c_position").val(co_position.text());

            var civil = "<option value='"+civil_status.text().toLowerCase()+"' selected>"+civil_status.text()+"</option>"

            var cv_status = $("#cstatus");
            // remove prepended element
            cv_status.find("option[selected]").remove();
            // prepend new civil status
            cv_status.prepend(civil);

            var shift_select = $("#shift");
            // remove any custom shift if there is any
            shift_select.find(".custom_shift").remove();
            // prepend the option
            shift_select.prepend($shift);


            $("#edit_modal").modal({
                keyboard: false
            });

        });
        //save
        var save_btn = $("#save_emp_info");

        save_btn.on("click", function(){
            var $this = $(this);

            var url = $("#emp_edit_url").val();
            var formdata = $("#edit_employee").serialize();

            var modal_content = $this.closest(".modal-content");
            var loader = modal_content.find(".loader");

            var notification = $("#notification_msg");

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
                        // replace token
                        $('#csrftoken').html(response["token"]);

                        // update info
                        // the record
                        var $record = $("#"+response["userid"]);

                        $record.find(".fname").text(response["fname"]);
                        $record.find(".mname").text(response["mname"]);
                        $record.find(".lname").text(response["lname"]);

                        $record.find(".cstatus").text(response["civil_status"]);
                        $record.find(".birth_date").text(response["birthdate"]);
                        $record.find(".shift-name").text(response["shift"]);

                        $record.find(".created_at").text(response["created_at"]);
                        $record.find(".last_update").text(response["last_update"]);
                        $record.find(".last_pwd_change").text(response["last_pwd_change"]);
                        $record.find(".last_login").text(response["last_login"]);

                        loader.addClass("hide");
                        notification.find("p.message_title").addClass("text-success").text(response["success"]);
                        notification.find("p.message_content").text(response["employee information updated"]);
                        notification.removeClass("hide");
                    }else{
                        console.log(response);
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
        // close panel info
        var closeBtn = $("#close_info")

        closeBtn.on("click", function(){
            var $this = $(this);
            $this.parent().addClass("hide");
            $("#edit_modal").modal("hide");
        });


        // delete
        var emp_delete = $(".employees .emp_delete");

        emp_delete.on("click", function(){

            var $this = $(this);
            // this delete button's parent container
            var $this_container = $this.closest(".employee-info");
            // fullname
            var fullname = $this_container.find(".record .info .emp_fullname");

            // break down of employees full name
            var fname = fullname.find(".fname");
            var mname = fullname.find(".mname");
            var lname = fullname.find(".lname");
            // get userid from attribute data-userid
            var userid = $this.attr("data-userid");
            // put the value on the hidden input
            var dlt_input = $("#delete_userid");
            dlt_input.val(userid);

            $("#delete_modal").find(".delete_emp_name .name").text(fname.text()+" "+mname.text()+" "+lname.text());
            $("#delete_modal").modal({
                keyboard: false
            });
        });

        // delete by clicking the delete button
        var dlt_btn = $("#deletebtn");

        dlt_btn.on("click", function(){


            var $this = $(this);

            var url = $("#emp_delete_url").val();
            var formdata = $("#deleteform").serialize();

            var modal_content = $("#deleteform").find(".modal-content");
            var loader = modal_content.find(".loader");

            var notification = $("#delete_modal").find(".notification_msg");

            var userid   = $("#delete_userid").val();
            var udisplay = $("#"+userid);

            $.ajax({
                type: "post",
                url: url,
                data:formdata,
                async: true,
                beforeSend: function(){
                    // display loader
                    loader.removeClass("hide")
                },
                success: function (response) {
                    if(response === "0" || response === "" || response === null || response === undefined){
                        loader.addClass("hide");
                        notification.find("p.message_title").addClass("text-danger").text("error");
                        notification.find("p.message_content").text("Failed to delete user.");
                        notification.removeClass("hide");
                    }
                    if(response === "1"){
                        loader.addClass("hide");
                        notification.find("p.message_title").addClass("text-success").text("Success");
                        notification.find("p.message_content").text("Employee record deleted");
                        notification.removeClass("hide");
                        udisplay.remove();
                    }
                },
                error: function(){

                }
            });
            
        });

        var dlt_cls_mdl_btn = $("#delete_modal").find(".close_info");
        dlt_cls_mdl_btn.on("click", function(){
            var $this = $(this);
            $this.parent().addClass("hide");
            $("#delete_modal").modal("hide");
        });

        if($("#bdate").length === 1){
            $( "#bdate" ).datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                yearRange: "1900:2100"
            });
        }

    }

});