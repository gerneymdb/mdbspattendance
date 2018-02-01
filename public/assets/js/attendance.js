$(document).ready(function(){

    // $('#attendance-body').scroll(function(e) { //detect a scroll event on the tbody
    //     /*
    //      Setting the thead left value to the negative valule of tbody.scrollLeft will make it track the movement
    //      of the tbody element. Setting an elements left value to that of the tbody.scrollLeft left makes it maintain
    //      it's relative position at the left of the table.
    //      */
    //     $('#attendance-heading').css("left", -$("tbody").scrollLeft()); //fix the thead relative to the body scrolling
    //     $('#attendance-heading th:nth-child(1)').css("left", $("tbody").scrollLeft()); //fix the first cell of the header
    //     $('#attendance-body td:nth-child(1)').css("left", $("tbody").scrollLeft()); //fix the first column of tdbody
    // });

    if($("#manage-attendance").length === 1){

        $("#from").datepicker();
        $("#to").datepicker();

        // if present button is click
        var prsnt_btn = $(".btn-attdnc-present");
        prsnt_btn.on("click", function(){
            // refering to this button
            var $this = $(this);

            // input field on the edit present modal
                //date of attendance
                var dateofattendance = $("#date_of_attendance");
                // fullname
                var fullname = $("#fullname_timein");
                // the the date of when user timed in
                var datetimein = $("#datetimein");
                // the hr when user timed in on that date
                var hrtimein = $("#hrtimein");
                // the min when user timed in on that date
                var mintimein = $("#mintimein");
                // the sec when user timed in on that date
                var sectimein = $("#sectimein");

                // the the date of when user timed out
                var datetimeout = $("#datetimeout");
                // the hr when user timed out on that date
                var hrtimeout = $("#hrtimeout");
                // the min when user timed out on that date
                var mintimeout = $("#mintimeout");
                // the sec when user timed out on that date
                var sectimeout = $("#sectimeout");

                // that status of the user's attendance on that date
                var status = $("#timeinstatus");
                // hidden field for the attendance id;
                var attendance_id = $("#attendance_id");
            // end of input fields

            // remove previously prepended element
            // timein
            hrtimein.find("option.select").remove();
            mintimein.find("option.select").remove();
            sectimein.find("option.select").remove();
            // timeout
            hrtimeout.find("option.select").remove();
            mintimeout.find("option.select").remove();
            sectimeout.find("option.select").remove();

            status.find("option.select").remove();

            // populate the fields
                dateofattendance.val($this.attr("id"));
                fullname.val($this.attr("data-name"));
                attendance_id.val($this.attr("data-att-id"));

                // split the data-timein to get the datetime, hrtimein, mintimein, and sectimein
                var timein = $this.attr("data-timein");
                timein = timein.split(" "); // output =  ['2017-12-18', '09:00:00']; ex.
                // the date
                var date = timein[0];
                datetimein.val(date);
                // time of timein
                var intime = timein[1].split(":"); // output = ['09', '00', '00'] ex.
                var timeinhr  = intime[0];
                var timeinmin = intime[1];
                var timeinsec = intime[2];

                hrtimein.prepend("<option value='"+timeinhr+"' selected class='select'>"+timeinhr+"</option>");
                mintimein.prepend("<option value='"+timeinmin+"' selected class='select'>"+timeinmin+"</option>");
                sectimein.prepend("<option value='"+timeinsec+"' selected class='select'>"+timeinsec+"</option>");

                // status
                status.prepend("<option value='"+$this.attr('data-status')+"' selected class='select'>"+$this.attr("data-status")+"</option>");

                // timeout field
                // split the data-timein to get the datetime, hrtimein, mintimein, and sectimein
                var timeout = $this.attr("data-timeout");

                if(timeout.length !== 0) {

                    timeout = timeout.split(" "); // output =  ['2017-12-18', '18:00:00']; ex.

                    // the date
                    var dateout = timeout[0];
                    datetimeout.val(dateout);

                    if(timeout.length === 2){

                        // time of timeout
                        var outtime = timeout[1].split(":"); // output = ['09', '00', '00'] ex.
                        var timeouthr = outtime[0];
                        var timeoutmin = outtime[1];
                        var timeoutsec = outtime[2];

                        hrtimeout.prepend("<option value='" + timeouthr + "' selected class='select'>" + timeouthr + "</option>");
                        mintimeout.prepend("<option value='" + timeoutmin + "' selected class='select'>" + timeoutmin + "</option>");
                        sectimeout.prepend("<option value='" + timeoutsec + "' selected class='select'>" + timeoutsec + "</option>");
                    }
                }

            $("#edt_present").modal({
                keyboard: false
            });

        });

        // save edit present changes
        var btn_edit_present = $("#edit_present_btn");
        btn_edit_present.on("click", function(){

            var $this = $(this);

            var url = $("#present_url").val();
            var formdata = $("#present_edit").serialize();

            var modal_content = $this.closest(".modal-content");
            var loader = modal_content.find(".loader");

            var notification = $("#edt_present").find(".notification_msg");

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
                        $("#atteditcsrftoken").html(response['token']);
                        var row = $("#"+response['id']);
                        console.log(row);
                        if(Object.keys(response).length > 1){
                            // turn into absent
                            row.removeClass("btn-success");
                            row.removeClass("btn-attdnc-present");
                            row.addClass("btn-danger");
                            row.addClass("btn-attendance-absent");
                            row.removeAttr("data-timein");
                            row.removeAttr("data-timeout");
                            row.removeAttr("data-status");

                            //change the id
                            var current_id = response['id'];
                            var new_id = current_id.substr(0,-2);
                            row.attr('id', new_id);
                        }else {
                            // edit present
                            row.attr("data-timein", response['timein']);
                            row.attr("data-timeout", response['timeout']);
                            row.attr("data-status", response['status']);
                        }

                        loader.addClass("hide");
                        notification.find("p.message_title").addClass("text-success").text(response["success"]);
                        notification.find("p.message_content").text(response["attendance information updated"]);
                        notification.removeClass("hide");

                        console.log(response);
                    }


                },
                error: function(response){
                    var info = response.responseText.split(":");

                    loader.addClass("hide");
                    notification.find("p.message_title").addClass("text-danger").text(info[1]);
                    notification.find("p.message_content").text(info[0]);
                    notification.removeClass("hide");
                    console.log(info)
                }
            });

        });
        // close notification
        var cls_mdl_btn_prsnt = $("#edt_present").find(".close_info");
        cls_mdl_btn_prsnt.on("click", function(){
            var $this = $(this);
            $this.parent().addClass("hide");
            // $("#edt_present").modal("hide");
        });


        // if absent button is click
        var absnt_btn = $(".btn-attendance-absent");
        absnt_btn.on("click", function(){

            var $this = $(this);

            // employees userid
            var userid   = $this.attr("data-userid");
            // the date selected
            var thisdate = $this.attr("data-date");
            // employees name
            var fullname = $this.attr('data-name');

            // input fields
            var i_fullname    = $("#absent_fullname");
            var a_datetimein  = $("#a_datetimein");
            var a_datetimeout = $("#a_datetimeout");
            var a_userid      = $("#userid");

            // populate this fields
            i_fullname.val(fullname);
            a_datetimein.val(thisdate);
            a_datetimeout.val(thisdate);
            a_userid.val(userid);

            $("#edt_absent").modal({
                keyboard: false
            });


        });

        // save changes
        var btn_edt_absent = $("#edt_btn_absent");
        btn_edt_absent.on("click", function(){

            var $this = $(this);

            var url = $("#absent_url").val();
            var formdata = $("#absent_edit").serialize();

            var modal_content = $("#edt_absent").find(".modal-content");
            var loader = modal_content.find(".loader");

            var notification = $("#edt_absent").find(".notification_msg");

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

                    console.log(response);
                    console.log(typeof response);

                    if(response === "0" || response === "" || response === null || response === undefined){
                        loader.addClass("hide");
                        notification.find("p.message_title").addClass("text-danger").text("error");
                        notification.find("p.message_content").text("Failed to update attendance information.");
                        notification.removeClass("hide");
                    }
                    if(response === "1"){

                        loader.addClass("hide");
                        notification.find("p.message_title").addClass("text-success").text("Success");
                        notification.find("p.message_content").text("Attendance information updated");
                        notification.removeClass("hide");

                        setTimeout(function(){
                            $("#search_attendance").submit();
                        }, 1000);

                    }
                    if(response === "No changes"){
                        loader.addClass("hide");
                        notification.find("p.message_title").addClass("text-success").text("Nothing");
                        notification.find("p.message_content").text("No changes we're made");
                        notification.removeClass("hide");
                    }

                },
                error: function(){

                }
            });

        });
        // close notification
        var cls_mdl_btn_absnt = $("#edt_absent").find(".close_info");
        cls_mdl_btn_absnt.on("click", function(){
            var $this = $(this);
            $this.parent().addClass("hide");
            $("#edt_absent").modal("hide");
        });


        var attendance_count = $("#attendance_count");
        attendance_count.on("click", function(){


            $("#attendance_count_modal").modal({
                keyboard: false
            });
        });

        if($("#datetimeout").length === 1){
            $( "#datetimeout" ).datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                yearRange: "1900:2100"
            });
        }
        if($("#datetimein").length === 1){
            $( "#datetimein" ).datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                yearRange: "1900:2100"
            });
        }

    }

});