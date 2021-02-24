$(document).ready(function() {
$('.profile-wrap').on('dragstart', 'img', function(event) { event.preventDefault(); });

$("#userImages").sortable({
	
	containment: 'parent', 
	tolerance: 'pointer', 
	cursor: 'pointer'
	
});

var overlay = $("<div id='overlay'></div>");
var image = $("<img id='userImage' class='noclick'>");
var likebutton = $('<button title="" class="noclick like_heart_float"></button>');
var postdate = $('<div class="feed_time">99 days</div>');
var close = $("<img id='closeImage'>");
var container = $("<div id='container' class='noclick'></div>");
var info = $("#imgUserInfo");

$("body").append(overlay);
container.append(postdate,image,info);
overlay.append(container,close,likebutton);

$.fn.disableScroll = function() {
    window.oldScrollPos = $(window).scrollTop();

    $(window).on('scroll.scrolldisabler',function ( event ) {
       $(window).scrollTop( window.oldScrollPos );
       event.preventDefault();
    });
};
$.fn.enableScroll = function() {
    $(window).off('scroll.scrolldisabler');
};
$("#userImages a").click(function(e) {
	
	$("html").disableScroll();
	$("html").css("position:fixed");
	e.preventDefault();
    var liked = 0;
    if (typeof $(".like_heart_float").data("liked") === 'undefined')
        liked = $("#inputcomment").attr("liked"); //Retrieve the original status coming from backend
    else
        liked = $(".like_heart_float").data("liked"); //Retrieve the updated status from js
    if (liked == 0) {
        likebutton.data("type","like");
        likebutton.attr("title","Like");
    } else {
        likebutton.data("type","unlike");
        likebutton.attr("title","Unlike");
        likebutton.addClass("clicked");
    }
    likebutton.data("imageid", $("#inputcomment").attr("image_id"));
	var imageSource = $(this).attr("href");
	image.attr("src", imageSource);
	close.attr("src", "/assets/images/close.png");
    likeUser();
	overlay.show();
	
});

close,$("#overlay").click(function(e) {
	if ($(e.target).hasClass('noclick')) {
        if ($(e.target).is('#sendcomment')) {
            sendComment($(e.target));
            var tmp = $(this).siblings().find(".showcomments").text().split('(');
            $(this).siblings().find(".showcomments").text(tmp[0] + " (" + (tmp[1].slice(0,-1)+1) + ")");
        }
        return;
    } else  {
    	$("html").enableScroll();
	   	$(overlay).hide();
    }
});

});
