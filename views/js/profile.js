$(document).ready(function() {
    $(".chat_btn").click(function() {
        window.location.href = "/chat/?u=" + $(this).data("user");
    });

    $(".user_settings_btn").click(function(e) {
        $(".usersettingsmenu").remove(); //Remove previously opened menus
        var blockitem = '<a class="blockuser" href="#">Block</a>';
        //Check if the user was blocked
        if ($(".user_blocked_profile").length > 0)
            blockitem = '<a class="unblockuser" href="#">Unblock</a>';
        var menu = $('<ul class="dropdown-menu usersettingsmenu" style="position: absolute; display:block;"><li>'+blockitem+'</li><li><a class="reportuser" href="#">Report</a></li></ul>');
        menu.css({left:e.pageX, top:e.pageY});
        menu.appendTo("body");
        //Take the userid and save it into our (independent) contextmenu
        menu.data("userid", $(this).data("userid"));
        menu.mouseleave(function() { $(".usersettingsmenu").remove(); });
        menu.find(".reportuser").click(function() {
            var postid = $(this).parent().parent().data("userid");
            $(".usersettingsmenu").remove();
            $.post(url + "/feed/reportUser", { 'userid' : postid }).done(function(data) {});
        });
        menu.find(".blockuser").click(function() {
            var postid = $(this).parent().parent().data("userid");
            $(".usersettingsmenu").remove();
            $.post(url + "/feed/blockUser", { 'userid' : postid }).done(function(data) { window.location.reload(); });
        });
        menu.find(".unblockuser").click(function() {
            var postid = $(this).parent().parent().data("userid");
            $(".usersettingsmenu").remove();
            $.post(url + "/feed/unblockUser", { 'userid' : postid }).done(function(data) { window.location.reload(); });
        });

    });


});


/*
function showImage(id,liked) {
//Clear old data
$('#image_comments').empty();
//Setup the comment box
$('#inputcomment').attr('image_id', id);
$('#inputcomment').attr('liked', liked);

//Load comments
    $.ajax({
        type: "GET",
        url: url + "/image/comments/" + id,
        data: {
            },
            success: function(data){
                $('#image_comments').append(data);
            }
        });
}
*/
