$ ("#btn1").prop("disabled",  true);

$("#id, #pass").keyup(function(){
    var value1=$("#id").val().trim();
    var value2=$("#pass").val().trim();
    if(value1=="" || value2==""){
        $("#btn1").prop("disabled", true);
    }
    else{
        $("#btn1").prop("disabled", false);
    }
});

$ ("#btn2").prop("disabled",  true);

$("#np, #cp").keyup(function(){
    var value1=$("#np").val().trim();
    var value2=$("#cp").val().trim();
    if(value1=="" || value2==""){
        $("#btn2").prop("disabled", true);
    }
    else{
        $("#btn2").prop("disabled", false);
    }
});
$ ("#btn3").prop("disabled",  true);

$("#passs, #confirms").keyup(function(){
    var value1=$("#passs").val().trim();
    var value2=$("#confirms").val().trim();
    if(value1=="" || value2==""){
        $("#btn3").prop("disabled", true);
    }
    else{
        $("#btn3").prop("disabled", false);
    }
});

$ ("#btn4").prop("disabled",  true);

$("#di, #wordpass").keyup(function(){
    var value1=$("#di").val().trim();
    var value2=$("#wordpass").val().trim();
    if(value1=="" || value2==""){
        $("#btn4").prop("disabled", true);
    }
    else{
        $("#btn4").prop("disabled", false);
    }
});
$(document).ready(function(){

    $("#pass").on("change",function() {

        if ($("#id").val() == $("#pass").val()) {

            $("#match").hide();

        } else {

            $("#match").show();

        }

        var a = $("#id").val();
        var b = $("#pass").val();


    });

});

$(document).ready(function(){

    $("#cp").on("change",function() {

        if ($("#np").val() == $("#cp").val()) {

            $("#match").hide();

        } else {

            $("#match").show();

        }

        var a = $("#np").val();
        var b = $("#cp").val();


    });

});

$(document).ready(function(){

    $("#confirms").on("change",function() {

        if ($("#passs").val() == $("#confirms").val()) {

            $("#match").hide();

        } else {

            $("#match").show();

        }

        var a = $("#passs").val();
        var b = $("#confirms").val();


    });

});

$(document).ready(function(){

    $("#wordpass").on("change",function() {

        if ($("#di").val() == $("#wordpass").val()) {

            $("#match").hide();

        } else {

            $("#match").show();

        }

        var a = $("#di").val();
        var b = $("#wordpass").val();


    });

});





