$(document).ready(function () {
    $("#btnSend").click(function () {
        $.post('contactus/sendEmail', { message : $("#message_content").val() }, function(data) {
            $("#btnSend").animate({top: '-5px'}, 150, function() { $("#btnSend").animate({top: '0'}, 150, function() {$("#btnSend").text(data);}); $("#btnSend").css('background-color', 'rgb(72, 223, 29)'); });
            $("#btnSend").unbind("click");
        });
    });
    $("#btnCancel").click(function() {
        window.history.back();
    });
});
