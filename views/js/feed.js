
function gup( name ) {
	var lurl = window.location.href;
    name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
    var regexS = "[\\?&]"+name+"=([^&#]*)";
    var regex = new RegExp( regexS );
    var results = regex.exec( lurl );
    return results == null ? null : results[1];
}

$(document).ready(function() {

	$('#postfeed').on('dragstart', 'img', function(event) { event.preventDefault(); });
    var flag = 0;

    function loadPosts() {
		var user = gup('u');
	    $.ajax({
	    	type: "GET",
	    	url: url + "/feed/posts",
	    	data: {
	    		'offset':flag,
	    		'limit': 9,
                'user' : user
	    	},
	    	success: function(data){
	    		
	    		$('#all_feed_content').append(data);
	    		likeUser();
	    		doubleclicklike();
	    		flag += 9;
                $(".photosettings").unbind("click");
                $(".photosettings").click(function(e) {
                    if ($(".photomenu").length > 0) {
                        $(".photomenu").remove(); //Remove previously opened menus
                    } else {
                        var menu = $('<ul class="dropdown-menu photomenu" style="position: absolute; display:block;"><li><a class="deletepost" href="#">Delete</a></li><li><a class="reportpost" href="#">Report</a></li></ul>');
                        menu.css({left:e.pageX, top:e.pageY});
                        menu.appendTo("body");
                        //Take from the parent the postid and save it into our (independent) contextmenu
                        menu.data("postid", $(this).parent().parent().data("postid"));
                        menu.mouseleave(function() { $(".photomenu").remove(); });
                        menu.find(".deletepost").click(function() {
                            var postid = $(this).parent().parent().data("postid");
                            $(".photomenu").remove();
                            $.post(url + "/feed/deletePost", { 'postid' : postid }).done(function(data) {
                                if (data == "success") {
                                    $("#post"+postid).animate({opacity: '0'}, 1000, function() {
                                        $("#post"+postid).remove();
                                    });
                                } else {
                                    $("#post"+postid).css({outline: "0px solid transparent"})
                                    .animate({ outlineWidth: '5px', outlineColor : '#e8143a' }, 400, function() {
                                        $(this).animate({ outlineWidth: '0px'}, 400);
                                    });
                                }
                            });
                        });
                        menu.find(".reportpost").click(function() {
                            var postid = $(this).parent().parent().data("postid");
                            $(".photomenu").remove();
                            $.post(url + "/feed/reportPost", { 'postid' : postid }).done(function(data) {
                                if (data == "success") {
                                    $("#post"+postid).find(".feed_time").text("Reported");
                                }
                            });
                        });

                    }
                });
                $(".image-wrap").unbind("click");
                $(".image-wrap").click(function() {
                    p = $(this).parent();
                    p.find(".showcomments").toggle();
                    if (p.hasClass("col-sm-4"))
                        p.removeClass("col-sm-4").addClass("col-sm-8");
                    else
                        p.removeClass("col-sm-8").addClass("col-sm-4");
                    p.find("#image_comments").toggle();
                });

	    	}
	    
	    });
    }


    loadPosts();

	//ajax end
	$(window).scroll(function() {
	    if($(window).scrollTop() >= $(document).height() - $(window).height()) {
            loadPosts();
	    }
	});
	
});
