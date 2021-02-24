<?php
if(!empty(ANNOUNCE)) {
    if (!isset($_COOKIE['read_announce'])) {
        echo '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">x</button><strong>Announce: </strong>'.ANNOUNCE.'</div>';
        setcookie("read_announce", "done", time() + (86400 * 7), "/"); // 86400 = 1 day
    }
} else {
    //Destroy the cookie
    setcookie("read_announce", "done", time() - 3600, "/");
}
?>
