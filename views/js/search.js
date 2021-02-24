$(document).ready(function() {
    $(".searchresult").click(function() {
        window.location.href = "user/" + $(this).data("username");
    });

    $(".sidebarheader").click(function() {
        if ($(".sidebarcontent").is(":hidden")) {
            $(".sidebarcontent").show();
            $(".sidebarcontent").animate({height: '270px'}, 500);
        } else {
            $(".sidebarcontent").animate({height: '0'}, 500, function() { $(this).hide(); });
        }
    });

    if ($(window).width() > 970)
        $(".sidebarheader").click();

    $(window).resize(function () {
        $(".sidebarheader").click();
    });
});
