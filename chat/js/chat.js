//Global Constants
const PROFILE_IMAGES_PATH = "/assets/images/profile/";
const UPLOAD_DIR = "uploads/";
const CHAT_TITLE = "Halfp";
// ----- 
function getCookie(name) {
    var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    if (match) return match[2];
}

//Load the correct language
if (getCookie("lang") == "it") {
    $.getJSON("js/lang/it.json", function(data) { languageLoaded(data);});
} else {
    //English as default
    $.getJSON("js/lang/en.json", function(data) { languageLoaded(data);});
}

function languageLoaded(LANG) {

//Load all the dynamic contents

function formatTime(time) {
    var datetime = new Date(time.replace(" ", "T") + "Z");
    var minutes = datetime.getMinutes();
    if (minutes < 10)
        minutes = '0'+minutes;
    return (datetime.getHours()+':'+minutes+' '+datetime.getDay()+'/'+(datetime.getMonth()+1)+'/'+datetime.getFullYear());
}

function showNotification(msg) {
    var notification = '<div class="notification">'+msg+'</div>';
    $(document.body).append(notification);
    $(".notification").animate({left: '-=20%'});
    setTimeout(function() { $(".notification").animate({opacity: '0'}, function() { $(".notification").hide(); }); }, 5000);
}
function showError(msg) {
    showNotification("<b>"+LANG.Error+":</b><br>" + msg);
}

$.get("api/getUserInfo").done( function (me) {
if (me.error !== undefined) {
    showError(me.error);
    return;
}

function param(name) {
    return (location.search.split('?' + name + '=')[1] || location.search.split('&' + name + '=')[1] || '').split('&')[0];
}

function loadChat(chatid) {
    if (chatid == null)
        return;
    $("#inputcomment").val("");
    $.get( "api/getChatMessages/"+chatid)
    .done(function( messages ) {
        if (messages.error !== undefined) {
            showError(messages.error);
            return;
        }
        $(".div_of_chat").empty();
        updateMessagesInfo(messages, chatid);
    });
}

function showMessage(message) {
    var divhtml;
    var isRead = "";
    if (message.srcuser.id == me.id) {
        divhtml = '<div class="my_row"><div class="my_message_div">';
        if (message.read == 1)
            isRead = "✓ ";
    } else {
        divhtml = '<div class="row"><div class="message_div">';
    }
    var content = "";
    if (message.type == "text") {
        content = replaceEmoji(message.content);
    } else if (message.type == "file") {
        var url = UPLOAD_DIR+'/'+message.content;
        content = '<img class="chatphoto" src="'+url+'" onclick="window.open('+"'"+url+"'"+')"></img>';
    }
    divhtml += content+'<br><div class="below_message_div">'+isRead+formatTime(message.time)+'</div></div></div><div class="spacing" />';
    $(".div_of_chat").append(divhtml);
}

function updateChat(chatid){
    setTimeout(function(){
        //Update only the messages of the current active chat
        if (chatid == $("#inputmessage").data("chatid")) {
            loadLastMessages(chatid);
        }
    }, 30*1000);
}

function updateMessagesInfo(messages, chatid) {
    var data = "";
    var datai = 0;
    $.each(messages, function(i, message) {
        showMessage(message);
        if ((message.srcuser.id != me.id) && (message.read == 0)) {
            data += "&id["+datai+"]=" + message.id;
            datai++;
        }
    });
    if (datai > 0) {
        //Set messages as read
        $.post("api/setReadMessages", data);
    }
    //Continue to sync for new messages
    updateChat(chatid);
}

function loadLastMessages(chatid) {
    //This function is used for real-time chatting
    var limit = 6;
    $.get( "api/getLastChatMessages/"+chatid+"/"+limit)
    .done(function( messages ) {
        if (messages.error !== undefined) {
            showError(messages.error);
            return;
        }
        //Remove last messages
        for (var i = 0; i < limit; i++) {
            $(".div_of_chat div").last().remove(); //Remove the spacing
            $(".div_of_chat div").last().remove(); //Remove the message time
            $(".div_of_chat div").last().remove(); //Remove the message content
            $(".div_of_chat div").last().remove(); //Remove the row
        }
        updateMessagesInfo(messages.reverse(), chatid);
    });
}

$(".div_of_chat").ready(function() { loadChat(null); });

function replaceEmoji(msg) {
    $.each($(".emoji"), function(i, emoji) {
        var searched = ":"+$(emoji).attr("id")+":";
        //Use the while+replace instead of replaceAll for older browsers
        while (msg.indexOf(searched) >= 0)
            msg = msg.replace(searched, $(emoji).html());
    });
    return msg;
}

function sendMessage() {
    if ($("#inputmessage").val() == "")
        return;
    $.post("api/postNewMessage", { msg: $("#inputmessage").val(), to: $("#inputmessage").data("dstuser"), type: "text"}).done(function(data) {
        if (data.error !== undefined) {
            showError(data.error);
            return;
        }
/*        if (data.status == "success")
            $("#press_enter").html('<div id="clr_g" style="margin-bottom:-3px;"> <b> &#10003 Sent</b></div>');*/
    });
    $("#inputmessage").val("");
    loadLastMessages($("#inputmessage").data("chatid"));
}

$(document).ready(function () {
    $(".overlay").click(function() {
        $(".profilebig").animate({height: '0', width: '0'}, 300, function() {
            $(".overlay").css("display", "none");
        });
    });
    function checkNewMessages() {
        //If we have unread messages from other chats update sidebar entries
        $.get("api/getUnreadMessages").done(function(data) {
            if (Object.keys(data).length > 0) {
                loadContacts();
                //wait some time to load contacts.. even if we didn't waited enough this is not an issue
                (new Promise(resolve => setTimeout(resolve, 3000))).then(() => { //sleep
                $.each(data, function(i,o) {
                    $("#"+o.chat_id).addClass("newMessage");
                });
                });
            }
        });
    }
    function updateStatusContacts() {
        //Update status of contacts
        $.each($(".sidebar_entry"), function(i,e) {
            updateStatus($(e).data("userid"));
        });
    }
    function updateBlock() {
        if ($("#inputmessage").data("dstuser") !== 'undefined') {
            $.get("api/didIBlock/"+$("#inputmessage").data("dstuser")).done(function(res) {
                if (res.blocked) {
                    $("#buttonBlock").html("&#x1f513;");
                    $("#buttonBlock").attr("title",LANG.Unblock);
                    $("#buttonBlock").attr("class", "buttonUnblock");
                } else {
                    $("#buttonBlock").html("&#x1f512;");
                    $("#buttonBlock").attr("title",LANG.Block);
                    $("#buttonBlock").attr("class","buttonBlock");
                }
                $("#buttonBlock").click(function() {
                    if ($(this).attr("class") == "buttonBlock") {
                        if (confirm(LANG.BlockUserConfirm)) {
                            $.get("api/blockOtherUser/"+$("#inputmessage").data("dstuser")).done(function() {
                                $("#buttonBlock").html("&#x1f513;");
                                $("#buttonBlock").attr("title",LANG.Unblock);
                                $("#buttonBlock").attr("class", "buttonUnblock");
                            });
                        }
                    } else {
                        $.get("api/UnblockOtherUser/"+$("#inputmessage").data("dstuser")).done(function() {
                            $("#buttonBlock").html("&#x1f512;");
                            $("#buttonBlock").attr("title",LANG.Block);
                            $("#buttonBlock").attr("class","buttonBlock");
                        });
                    }
                });

            });
        } else {
            setTimeout(function() { updateBlock(); }, 2000);
        }
    }
    setInterval(function(){ checkNewMessages(); }, 60*1000);
    loadContacts();
    (new Promise(resolve => setTimeout(resolve, 2000))).then(() => { //sleep
        checkNewMessages();
    });
    setInterval(function() { updateStatusContacts(); }, 60*1000);
    setTimeout(function() { updateBlock(); }, 2000);

    $("#buttonPhoto").click(function () {
        var input = document.createElement('input');
        input.type = 'file';
        input.onchange = e => {
            var file = e.target.files[0];
            var form_data = new FormData();
            form_data.append('file', file);
            $.ajax({
                url: 'api/upload/' + $("#inputmessage").data("dstuser"),
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post'}).done(function (response) {
                    if (response.error !== undefined) {
                        showError(response.error);
                        return;
                    }
                    loadLastMessages($("#inputmessage").data("chatid"));
                });
        }
        input.click();
    });
    $("#buttonEmoji").click(function () { $("#emojibox").toggle(); });
    $("#buttonSend").on("click", function(e) {
        sendMessage();
    });
    $("#inputmessage").on('keypress',function(e) {
    if(e.which == 13) {
        sendMessage();
    }});

    //Now that the document is loaded..
    function resizeContent() {
        /* Resize the chatbox based on the screen width (smartphone and tablet compatibility) */
        if ($(window).width() <= 1024) {
            $("#centerDiv").width($(window).width());
            $("#centerDiv").css("margin-right", '0');
            $("#centerDiv").css("margin-left", $(".sidebar").width()+'px');
            $("#headerDiv").css("margin-bottom", '0');
            $(".sidebar").css("position", "relative");
            $(".sidebar").width($(window).width());
            $(".sidebar").css("height", "300px");
            return true;
        } else {
            return false;
        }
    }
    resizeContent();
    $(window).resize(function(){
        if (!resizeContent()) {
            //Reload the page to reset original properties
            location.href = location.href;
        }
    });

    /* Translate text from html page */
    $(".div_of_chat").html("<noscript>"+LANG.EnableJS+"</noscript>"+LANG.SelectChat);
    $("#inputmessage").attr("placeholder", LANG.EnterMessage);
    $("#buttonSend").text(LANG.Send);
    $("#press_enter").text(LANG.PressEnter);
    /* end translation */

});

function updateStatus(userid) {
    $.get("api/getLastActivity/"+userid).done(function (data) {
        if (data.error !== undefined) {
            showError(data.error);
            return;
        }
        var last_activity = new Date(data.last_activity.replace(" ","T") + "Z");
        var date = new Date();
        var now_utc =  Date.UTC(date.getUTCFullYear(), date.getUTCMonth(), date.getUTCDate(),date.getUTCHours(), date.getUTCMinutes(), date.getUTCSeconds());
        var last_utc =  Date.UTC(last_activity.getUTCFullYear(), last_activity.getUTCMonth(), last_activity.getUTCDate(),last_activity.getUTCHours(), last_activity.getUTCMinutes(), last_activity.getUTCSeconds());
        var diff = Math.abs(now_utc - last_utc); //milliseconds
        var minutes = Math.floor((diff/1000)/60);
        if (minutes < 5) {
            $("#status_user"+userid).css("display","inline");
        } else {
            $("#status_user"+userid).css("display","none");
        }
    });
}

function updateLastMessage(chatid) {
    $.post("api/getLastChatMessages/"+chatid+"/1").done(function (data) {
        if (data.error !== undefined) {
            showError(data.error);
            return;
        }
        if (Object.keys(data).length > 0) {
            if (data[0].type == "text") {
                var msg = data[0].content;
                var limit = 15;
                if (msg.length > limit)
                    msg = msg.substring(0, limit) + "...";
                $("#lastmessage"+chatid).text(msg);
            } else {
                $("#lastmessage"+chatid).text("<image>");
            }
        }
    });
}

function contactClick(recipient) {
    //This function is called when an entry on the side bar is clicked
    $("#"+recipient.chat_id).removeClass("newMessage");
    $("#titlechat").text(recipient.users.username);
    $("#inputmessage").data("dstuser", recipient.users.id);
    loadChat(recipient.chat_id);
    $("#inputmessage").data("chatid", recipient.chat_id);
    $(".input_box").css("display", "block");
}
function initNewChat() {
    //Check if we should init a new chat
    var u = param("u");
    //We need to remove the parameter, so taht it is evaluated only one time
    //replace url without adding it to history
    window.history.replaceState({}, document.title, "/chat/");
    if (u == "")
        return;
    if (u.includes("/") || u.includes("..") || u.includes("<") || u.includes(">")) {
        showError("Invalid user");
        return;
    }
    $.get("api/getUserInfo/" + u).done(function (user) {
        if (user.error !== undefined) {
            showError(user.error);
            return;
        }
        //Check that we don't have already an open chat with the uid
        var new_user = true;
        $.each($(".sidebar_entry"), function(i,e) {
            if (user.id == $(e).data("userid")) {
                new_user = false;
                //Open the already existing chat
                $(e).click();
                return false;
            }
        });
        if (new_user) {
            $(".div_of_chat").text("");
            $("#titlechat").text(user.username);
            //Init new chat posting a special message
            $.post("api/postNewMessage", { msg: ":init:", to: user.id, type: "text"}).done(function(data) {
                $(".input_box").toggle();
                $("#inputmessage").data("chatid", data.chat_id);
                $("#inputmessage").data("dstuser", user.id);
            });
        }
    });
}

function loadContacts() {
    $.each($(".sidebar_entry"), function(i, e) { $(e).remove() });
    $.get("api/getChatRecipients").done(function (recipients) {
        if (recipients.error !== undefined) {
            showError(recipients.error);
            return;
        }
        $(".counter").text(Object.keys(recipients).length);
        $.each(recipients, function(i, recipient) {
            var entry = '<div id="'+recipient.chat_id+'" data-userid="'+recipient.users.id+'" class="sidebar_entry"><div class="deletebutton">x</div><img class="user_img" src="'+PROFILE_IMAGES_PATH+'/'+recipient.users.profile_image+'" /><div>'+recipient.users.username+' <span id="status_user'+recipient.users.id+'" title="online" style="color:#1ab03d;display: none;font-size: 20px;">•</span><div id="lastmessage'+recipient.chat_id+'" class="sidebar_desc"></div></div></div>';
            $(".sidebar").append($(entry).click(function(e) {
                if ($(e.target).attr("class") == "deletebutton") {
                    if (confirm(LANG.DeleteChatConfirm+recipient.users.username)) {
                        $.ajax({ url: 'api/deleteChat/'+recipient.chat_id, type: 'DELETE', success: function (data) {
                            if (data.error !== undefined) {
                                showError(data.error);
                                return;
                            }
                            }});
                        $(this).animate({opacity : '0'}, function() { $(this).remove(); });
                    }
                } else {//sidebar_entry click
                    contactClick(recipient);
                    //update last messages for all the contacts
                    $.each(recipients, function(i, r) {
                        updateLastMessage(r.chat_id);
                        $(".div_of_chat").scrollTop($(".div_of_chat")[0].scrollHeight - $(".div_of_chat")[0].clientHeight);
                    });
                }
                }));
            updateStatus(recipient.users.id);
            updateLastMessage(recipient.chat_id);
        });
        $(".user_img").click(function() {
            $(".profilebig").attr("src", $(this).attr("src"));
            $(".profilebig").css({height: '200px', width: '200px'});
            $(".overlay").css("opacity", 0);
            $(".overlay").css("display", "flex");
            $(".overlay").animate({opacity: '1'}, 100);
        })
        //If this is the first time we see the destination user, init a new chat
        initNewChat();
    });
}

$(".emoji").click(function () {
    $("#inputmessage").val($("#inputmessage").val() + " :" + $(this).attr("id") + ":");
    $("#inputmessage").focus();
});

});

$("#headerDiv").ready(function() {
    $(".chatTitle").text(CHAT_TITLE);
});

} //end of languageLoaded function
