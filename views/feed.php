<section class="block-wrap">
<?php
if ($this->showprofile) {
    echo '<div class="user_profile_information">
    <div class="container">
	  <div class="profile_page_1">
		<div id="user_info_box">
			<div class="user_profile_image_pos">
			    <img src="'.SITE_URL.'assets/images/profile/'.$this->userData['profile_image'].'" class="user_profile_image">
			</div>
		</div>
        <div class="user_stats_box">
            <div class="user_box_profile">
			    <div class="user_profile_name">'.htmlentities($this->userData['username']);
    if ($this->blocked) {
        echo '<span class="user_blocked_profile" title="'.STR_BLOCKED.'"><span class="glyphicon glyphicon-minus-sign"></span></span>';
    } elseif($this->userData['verified'] == 1) {
        echo '<span class="user_verified_profile"><span>'.STR_VERIFIED.'</span></span>';
    }
    echo '</div>
    <div class="user_follow_box">';
    if ($this->selfuser == false) {
        if($this->followCheck == false)
        {
            echo '<input type="submit" data-type="follow" data-utid="'.$this->userData['id'].'" class="user_follow_btn" value="'.STR_FOLLOW.'">';
            echo '<input type="submit" data-type="unfollow" data-utid="'.$this->userData['id'].'" class="user_follow_btn hidden" value="'.STR_UNFOLLOW.'">';
        } else {
            echo '<input type="submit" data-type="unfollow" data-utid="'.$this->userData['id'].'" class="user_follow_btn unfollow" value="'.STR_UNFOLLOW.'">';
            echo '<input type="submit" data-type="follow" data-utid="'.$this->userData['id'].'" class="user_follow_btn hidden" value="'.STR_FOLLOW.'">';
        }
        echo '<button type="submit" data-user="'.htmlentities($this->userData['username']).'" class="chat_btn">Chat</button>';
    } else {
        echo '<a href="'.APP_URL.'settings" style="text-decoration:none;"><input type="submit" class="edit_settings" value="'.STR_SETTINGS.'"></a>';
    }
    echo '<button type="submit" data-userid="'.$this->userData['id'].'" class="user_settings_btn"><span class="glyphicon glyphicon-cog"></span></button>';
    echo '</div></div><div class="user_profile_stats">
            <span class="user_follow_stat">'.$this->posts.'</span>
            <span class="user_stats"><span id="volgers">'.$this->followers.'</span></span>
            <span class="user_stats">'.$this->following.'</span></div>
            <div class="user_profile_description"><b>'.htmlentities($this->userData['username']).'</b> '.find_hashtag(htmlentities($this->userData['profile_bio'])).'
            </div>
          </div>
    </div>
    </div>
    </div>
    </div><hr>';
}
?>
      <div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="block-content">
					<div class="row photos-wrap">
						<div id="postfeed">
                                                    
							<div id="all_feed_content"></div>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</section>

