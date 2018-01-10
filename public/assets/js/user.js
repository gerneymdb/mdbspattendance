$(document).ready(function(){
    var attendance = $("#attendance").length;
    if(attendance === 1){
        var hr  = $("#hr");
        var min = $("#min");
        var sec = $("#sec");

        var $date = new Date();
        var hours = ($date.getHours() < 10)? "0" + $date.getHours() : $date.getHours();
        var minutes = ($date.getMinutes() < 10) ? "0" + $date.getMinutes() : $date.getMinutes();
        var seconds = ($date.getSeconds() < 10) ? "0" + $date.getSeconds() : $date.getSeconds();

        hr.text(hours);
        min.text(minutes);
        sec.text(seconds);

        setInterval(function(){
            var $date = new Date();

            hours = ($date.getHours() < 10)? "0" + $date.getHours() : $date.getHours();
            minutes = ($date.getMinutes() < 10) ? "0" + $date.getMinutes() : $date.getMinutes();
            seconds = ($date.getSeconds() < 10) ? "0" + $date.getSeconds() : $date.getSeconds();

            hr.text(hours);
            min.text(minutes);
            sec.text(seconds);
        }, 1000);

        // ajax request every min
        var url = $("#total_time_url").val();
        var total_time = $("#total_time");

        setInterval(function(){

            if($("#total_time").length === 1) {
                $.ajax({
                    type: "get",
                    url: url,
                    datatype: "text",
                    async: true,
                    contentType: false,
                    success: function (response) {
                       total_time.text(response);
                    }
                });
            }

        }, 60000);
    }

    var startdate = $("#leave-app-form").length;

    if(startdate === 1){

        $("#startdate").datepicker({
            altField: "#start_date",
            altFormat: "yy-mm-dd",
            autoSize: true
        });

        $("#enddate").datepicker({
            altField: "#end_date",
            altFormat: "yy-mm-dd",
            autoSize: true
        });

    }

});