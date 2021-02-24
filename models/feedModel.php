<?php 
class feedModel extends Model {
	
	function __construct() {
            parent::__construct();
	}
    public function checkBlockedUser($sID, $target_id) {
        //Check if the current user was blocked by the target user
        $stmt = $this->db->prepare("SELECT * FROM chat_blocks WHERE user_src_id = :sid AND user_dst_id = :tid");
        $stmt->execute([':sid' => $sID, ':tid' => $target_id]);
        return ($stmt->rowCount() > 0);
    }
    public function blockUser($sID, $target_id) {
        //Check if the user was already blocked
        if (!$this->checkBlockedUser($sID, $target_id)) {
            //Block the user
            $stmt = $this->db->prepare("INSERT INTO chat_blocks (user_src_id, user_dst_id) VALUES (:sid,:tid)");
            $stmt->execute([':sid' => $sID, ':tid' => $target_id]);
            //Blocked user must unfollow current user
            $stmt = $this->db->prepare("DELETE FROM user_following WHERE user_id = :tid AND following_id = :sid");
            $stmt->execute([':sid' => $sID, ':tid' => $target_id]);
        }
        return "success";
    }
    public function unblockUser($sID, $target_id) {
        $stmt = $this->db->prepare("DELETE FROM chat_blocks WHERE user_src_id = :sid AND user_dst_id = :tid");
        $stmt->execute([':sid' => $sID, ':tid' => $target_id]);
        return "success";
    }

    public function report($sID, $target_id, $type) {
        AjaxOnly();
        // Check if the user has already reported the post/user
        $stmt = $this->db->prepare("SELECT * FROM reports WHERE user_id = :sid AND target_id = :id AND type = :type");
        $stmt->execute([':sid' => $sID, ':id' => $target_id, ':type' => $type]);
        if ($stmt->rowCount() > 0)
            return;
        // Get the total number of reports for the post/user
        $stmt = $this->db->prepare("SELECT * FROM reports WHERE target_id = :id AND type = :type");
        $stmt->execute([':id' => $target_id, ':type' => $type]);
        $reports = $stmt->rowCount() + 1; //old reports plus the current one
        if ($reports >= REPORT_THRESHOLD) {
            if ($type == "post") {
                //Delete the post
                $stmt = $this->db->prepare("DELETE FROM user_images WHERE id = :id");
                $stmt->execute([':id' => $target_id]);
            } elseif ($type == "user") {
                $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
                $stmt->execute([':id' => $target_id]);
            }
            $stmt = $this->db->prepare("DELETE FROM reports WHERE target_id = :id AND type = :type");
            $stmt->execute([':id' => $target_id, ':type' => $type]);
        } else {
            //Insert the report
            $stmt = $this->db->prepare("INSERT INTO reports (user_id, target_id, type) VALUES (:sid,:tid,:type)");
            $stmt->execute([':sid' => $sID, ':tid' => $target_id, ':type' => $type]);
        }
        return "success";
    }

    public function deletePost($sID, $postid) {
        AjaxOnly();
        //Delete post
        $stmt = $this->db->prepare("DELETE FROM user_images WHERE user_id = :sid AND  id = :id");
        $stmt->execute([':sid' => $sID, ':id' => $postid]);
        //Deletion of associated likes and comments is performed by db
        return "success";
    }

        public function followUser($sID)
        {
            AjaxOnly();
            $userTwoId = $_POST['utid'];
            if ($this->checkBlockedUser($userTwoId, $sID))
                return;
            
            $stmt = $this->db->prepare("SELECT COUNT(following_id) FROM user_following WHERE user_id = :sid AND  following_id = :id");
            $stmt->execute([':sid' => $sID, ':id' => $userTwoId]);
            $rowCount = (int) $stmt->fetchColumn();
            
            if($rowCount == 0) 
            {
                $stm = $this->db->prepare("INSERT INTO user_following (user_id, following_id) VALUES (:sid, :id)");
                $stm->execute([':sid' => $sID, ':id' => $userTwoId]);
                
                $ssstmt = $this->db->prepare("INSERT INTO user_notif (user_id, user_two_id,date,activity) VALUES (:userId, :userTwoId,  UNIX_TIMESTAMP(), :activity)");
                  $ssstmt->execute([
                      ':userId' => $sID , 
                      ':userTwoId' => $userTwoId, 
                      ':activity' => "1"
                  ]);
                echo "success";
            }
        }
        public function unfollowUser($sID)
        {
            $userTwoId = $_POST['utid']; // wie gebruiker wilt volgen
            
            $stmt = $this->db->prepare("SELECT COUNT(following_id) FROM user_following WHERE user_id = :sid AND  following_id = :id");
            $stmt->execute([':sid' => $sID, ':id' => $userTwoId]);
            $rowCount = (int) $stmt->fetchColumn();
            
            if($rowCount == 1) 
            {
                $stm = $this->db->prepare("DELETE FROM user_following WHERE user_id = :sid AND following_id = :id");
                $stm->execute([':sid' => $sID, ':id' => $userTwoId]);
                
                $ssstmt = $this->db->prepare("DELETE FROM user_notif WHERE user_id = :userId AND user_two_id = :userTwoId AND activity = :activity");
                $ssstmt->execute([
                      ':userId' => $sID , 
                      ':userTwoId' => $userTwoId, 
                      ':activity' => "1"
                 ]);
                echo "success";
            }
        }
        public function search()
        {
            AjaxOnly();
            
            $output = null;
            
            $search = $_POST['searchVal'];
            $searchq = preg_replace("#[^0-9a-z]#i","",$search);
            
            $stm = $this->db->prepare("SELECT id, username, profile_image FROM users WHERE username LIKE :un OR firstname LIKE :fn OR surname LIKE :sn");
            $stm->execute([':un' => '%'.$search.'%', ':fn' => '%'.$search.'%', ':sn' => '%'.$search.'%']);
            
            if($stm->rowCount() == 0) 
            {
                $output = '<p class="searchUserResults" style="color:#a4a4a4">User does not exist</p>';
            } 
            else 
            {
                foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $row) 
                {
                    $name = $row['username'];
                    $image = $row['profile_image'];
                    
                    $output .= '<a href="'.APP_URL.'user/'.$name.'" class="userProfileLink"> <p class="searchUserResults"><img class="searchImage" src="'.SITE_URL.'assets/images/profile/'.$image.'" alt="'.$name.'"> '.$name.'</p> </a>';
                }
            }
            echo $output;
        }
        public function removeComment() 
        {
            echo "Remove comment";
        }
        public function addComment($sID) 
        {
            AjaxOnly();
            $imageId = $_POST['image_id'];
            $comment = htmlentities($_POST['comment']);
            
            if(trim($comment) == " " || empty($comment) || !is_numeric($imageId))
            {
                return;
            } else {
                if (strlen($comment) > 250)
                    $comment = substr($comment, 0, 250);
                $stm = $this->db->prepare("INSERT INTO image_comments (image_id, image_comment, date, user_id) VALUES (:imageId, :comment, UNIX_TIMESTAMP(), :sID)");
                $stm->execute([
                    ':imageId' => $imageId, 
                    ':comment' => $comment, 
                    ':sID' => $sID
                ]);
            }
        }
        
        public function likePost($sID) 
        {
            AjaxOnly();
            
            $userId = $sID;
            $imageId = intval($_POST['i']);

            if ($imageId == 0)
                return;
            
            $stm = $this->db->prepare("SELECT * FROM image_likes WHERE user_id = :userId AND image_id = :imageId");
            $stm->execute([':userId' => $userId, ':imageId' => $imageId]);
            
            if($stm->rowCount() == 0) 
            {
                $stmt = $this->db->prepare("SELECT * FROM user_images WHERE id = :image");
                $stmt->execute([':image' => $imageId]);
                $test = $stmt->fetch();

                $sstmt = $this->db->prepare("INSERT INTO image_likes (user_id, image_id) VALUES (:userId, :imageId)");
                $sstmt->execute([':userId' => $userId , ':imageId' => $imageId]);
                
                
                
               if($userId !== $test['user_id']) 
                {
                     $ssstmt = $this->db->prepare("INSERT INTO user_notif (user_id, user_two_id,date,activity,data) VALUES (:userId, :userTwoId,  UNIX_TIMESTAMP(), :activity, :data)");
                     $ssstmt->execute([
                         ':userId' => $userId , 
                         ':userTwoId' => $test['user_id'], 
                         ':activity' => "0", 
                         ':data' => $imageId
                     ]);
                }
                echo "success";
            }
        }
        
        public function unlikePost($sID) 
        {
           AjaxOnly();

            $userId = $sID;
            $imageId = intval($_POST['i']);

            if ($imageId == 0)
                return;
            
            $stm = $this->db->prepare("SELECT * FROM image_likes WHERE user_id = :userId AND image_id = :imageId");
            $stm->execute([':userId' => $userId, ':imageId' => $imageId]);
            
            if($stm->rowCount() == 1) 
            {
                $stmt = $this->db->prepare("DELETE FROM image_likes WHERE user_id = :userId AND image_id = :imageId");
                $stmt->execute([':userId' => $userId , ':imageId' => $imageId]);

                $sstmt = $this->db->prepare("DELETE FROM user_notif WHERE user_id = :userId AND data = :imageId");
                $sstmt->execute([':userId' => $userId , ':imageId' => $imageId]);
               
                echo "success";
            }
            
        }
        
        public function posts($sID, $sName, $offset, $limit, $user)
        {
            /* This function violates the MVC pattern, TODO: code refactoring */
            AjaxOnly();
            
            $filteruid = null;
            if ($user != null) {
                $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
                $stmt->bindParam(':username', $user);
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    $res = $stmt->fetch();
                    $filteruid = $res['id'];
                }
            }

            $sName = htmlentities($sName);
            /** See users followed by current user **/
            $first = $this->db->prepare("SELECT following_id FROM user_following WHERE user_id = :id");
            $first->bindParam(':id', $sID);
            $first->execute();

                if ($filteruid == null) {
                    $ids = $sID;
                    foreach($first->fetchAll(PDO::FETCH_ASSOC) as $frow)
                    {
                        $ids .= "," . $frow['following_id'];
                    }
                } else {
                    $ids = $filteruid;
                }

                $third = $this->db->prepare("SELECT * FROM user_images WHERE user_id IN(".$ids.") ORDER BY upload_date DESC LIMIT {$limit} OFFSET {$offset}");
                $third->execute();
                if ((!isset($_GET['u'])) && ($offset == 0) && ($third->rowCount() == 0)) {
                    echo '<div class="row"><div class="photo-box col-sm-4" style="margin: auto; float: none; margin-top: 30px; margin-bottom: 30px;"><div class="top_name">'.STR_EMPTYFEED.' <a href="'.APP_URL.'/search">'.STR_SEARCHUSERS.'</a></div></div></div>';
                }
                foreach($third->fetchAll(PDO::FETCH_ASSOC) as $row) {
                       $four = $this->db->prepare("SELECT * FROM users WHERE id = :rid");
                       $four->bindParam(':rid', $row['user_id']);
                       $four->execute();
                       foreach ($four->fetchAll(PDO::FETCH_ASSOC) as $userrow) {
                         echo  '    <div class="photo-box col-sm-4" data-postid="'.$row['id'].'" id="post'.$row['id'].'">
                                    <div class="top_name">
                                    <button class="photosettings"><i class="glyphicon glyphicon-option-vertical"></i></button>
				                    <div class="feed_time">'.hg($row['upload_date']).'</div>

                                    <div class="profile_photo"> <img src="'.SITE_URL.'assets/images/profile/'.$userrow['profile_image'].'" class="profile_photo_feed"><img src="'.SITE_URL.'assets/images/profile/'.$userrow['profile_image'].'" class="profile_photo_feed_big"></div>

                                    <div class="user_box">
                                    <div class="user_name_feed"><a class="n-link" href="'.APP_URL.'user/'.$userrow['username'].'">'.$userrow['username'].'</a></div>
			';
				
                            if($userrow['verified'] == 1) { 

                                    echo '<span class="user_verified_feed"><span>'.STR_VERIFIED.'</span></span>'; 

                            }
                            $imagerow = $row;
				echo '
				</div>
				</div>
				<div class="image-wrap">
				
				<img class="small-img" src="'.SITE_URL.'assets/images/posts/'.$row['image_url'].'">
				
				<div class="likes">
				';
                                $fifth = $this->db->prepare("SELECT * FROM image_likes WHERE image_id = :imgid");
                                $fifth->execute([':imgid' => $imagerow['id']]);
                                
				echo '<span class="_likesimage" id="'.$imagerow['id'].'" data-likes="'.$fifth->rowCount().'">❤️️ '.$fifth->rowCount().'</span>';
				echo '
				</div>
				</div>
				
				<div class="description">
				<div class="desc_box">
				<div class="user_name_feedbox"><a class="n-link" href="'.APP_URL.'user/'.$userrow['username'].'">'.htmlentities($userrow['username']).'</a></div>
				'.find_atuser(find_hashtag(str_replace("&lt;br /&gt;", "<br/>", htmlentities($row['image_description'])))).'
				</div>
				<div class="comments_box">';

                $comments = $this->db->prepare("SELECT * FROM image_comments WHERE image_id = :imgid");
                $comments->execute([':imgid' => $imagerow['id']]);
				
				echo '<div class="showcomments">'.STR_SHOWCOMMENTS.' ('.$comments->rowCount().')</div>
				<div id="image_comments" class="comments">';
				
                                
                                foreach($comments->fetchAll(PDO::FETCH_ASSOC) as $com) 
                                {
                                    $usercomName =  $this->db->prepare("SELECT * FROM users WHERE id = :comID");
                                    $usercomName->execute([':comID' => $com['user_id']]);
                                    
                                    $name = $usercomName->fetch(PDO::FETCH_ASSOC);
                                    
                                   echo '<p><a href="'.APP_URL.'/user/'.$name['username'].'" class="name">'.htmlentities($name['username']).'</a> <span>'.find_atuser(find_hashtag(htmlentities($com['image_comment']))).'</span></p>';
				
                                }
                              
                              
				echo'
				</div>
				<hr></hr>
				';
                                
                                $six = $this->db->prepare("SELECT * FROM image_likes WHERE user_id = :id AND image_id = :imgid");
                                $six->execute([':id' => $sID, ':imgid' => $imagerow['id']]);
				if($six->rowCount() > 0) {
					// Already liked
                                        echo '	
                                                <button title="Unlike" class="like_heart clicked" data-type="unlike" data-imageid="'.$imagerow['id'].'"> </button>
                                        ';
				} else {
					// Hasn't liked yet
                                        echo '	
                                                <button title="Like" class="like_heart" data-type="like" data-imageid="'.$imagerow['id'].'"></button>
                                            ';
				}
				echo '
				<input id="inputcomment" type="text" maxlength="250" name="comment" class="comments_input" image_id="'.$imagerow['id'].'" user="'.htmlentities($sName).'" placeholder="'.STR_LEAVECOMMENT.'...">
                <button id="sendcomment" type="submit" class="button" onclick="sendComment($(this));">'.STR_SEND.'</button>
				</div>
				
				</div>
				</div>
				';
                       }
                }
                
        } // function posts
        
} // class
