function searchq(e) {
	var searchVal = $("input[name='search']").val();
        
	$.post(url + "/feed/search", {searchVal: searchVal}, function(data) {
		
	if(searchVal == "")  {
			$("#searchData").hide();
			$("#searchData").html("");
		} else {
			$("#searchData").fadeIn();
			$("#searchData").html(data);
			}
	$(document).click(function(){  
	$('#searchData').hide(); //hide the button
	});
	});
}

function checkNewMessages() {
    $.get("/chat/api/getUnreadMessages").done(function(data) {
        if (!data.hasOwnProperty("error")) {
            var count = Object.keys(data).length;
            if (count > 0) {
                $(".counter").text(count);
                $(".counter").css("display","inline-block");
            }
        }
    });
}

$(document).ready(function() {
    checkNewMessages();
    setInterval(function() { checkNewMessages(); }, 60*1000);
});
