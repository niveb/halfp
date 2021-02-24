//Global variables
var url = $(location).attr('origin') + "/app/";
//-------
function sendComment(e) {
    var image_id = e.parent().children('#inputcomment').attr('image_id');
    var comment = e.parent().children('#inputcomment').val();
    $.post(url + '/feed/addComment' , {
        image_id: image_id,
        comment: comment
    });
    e.parent().children('#image_comments').append('<p><a href="user/" class="name">You</a> <span>' + comment + '</span></p>');
    e.parent().children('#inputcomment').val('');
    var tmp = e.parent().find(".showcomments").text().split('(');
    e.parent().find(".showcomments").text(tmp[0] + " (" + (parseInt(tmp[1].slice(0,-1))+1) + ")");
}

var likeUser = function() { 
        $(".like_heart, .like_heart_float").unbind(); //Remove all event handlers from these elements
		$(".like_heart, .like_heart_float").on('click', function () {
			var type = $(this).data('type');
			var imageid = $(this).data('imageid');
			var likeid = $("#" + imageid);
			var data = "i=" + imageid;
			// Liked
			if(type == 'like') {
				$(this).addClass('clicked');
                $(this).removeData("type");
				$(this).data("type", "unlike");
                $(this).removeData("liked");
                $(this).data("liked","1");
				if(imageid != ""){
				    $.ajax({
				    method: "post",
				    url: url + "/feed/likePost?",
				    data: data,
				    success: function(data){
				    	if(data == 'success') {
		    				var like = parseInt(likeid.data("likes"));
                            likeid.data("likes", (Number(like) + 1));
		    				likeid.text("❤️️ " + likeid.data("likes"));
				    	}
				    }
			        });
			    }
			} else if (type == 'unlike') {
			    $(this).removeClass('clicked');
                $(this).removeData("type");
				$(this).data("type", "like");
                $(this).removeData("liked");
                $(this).data("liked","0");
				if(imageid != ""){
			    	$.ajax({
			    	method: "post",
			    	url: url + "/feed/unlikePost?",
			    	data: data,
			    	success: function(data){
			    		
			    		if(data == 'success') {
		    				var like = parseInt(likeid.data("likes"));
                            likeid.data("likes", (Number(like) - 1));
		    				likeid.text("❤️️ " + likeid.data("likes"));
			    		}
			    	}
			        });
				}
				
			}
		});
	}
	
var doubleclicklike = function() {
		
		$(".block-content .photo-box .image-wrap img").on('dblclick', function () {
            //TODO
		});
		
}
	
