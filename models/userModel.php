<?php 
class userModel extends Model {
	
    function __construct() {
        parent::__construct();
    }
    public function getID($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $result = $stmt->fetch();
        return $result['id'];
    }
	public function getUserInfoByID($sID) 
        {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->execute([':id' => $sID]);
            $result = $stmt->fetch();	
            return $result;	
	}	
	public function verified($username = false) {
		
		$stmt =  $this->db->prepare("SELECT verified FROM users WHERE username = :username");
		$stmt->execute([':username' => $username]);
		
		return $stmt->fetch(PDO::FETCH_ASSOC);
		
	}
	public function checkUserExists($username = false) {

		// SELECT USERS WAAR USERNAME = $USERNAME
		$stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
		$stmt->execute([':username' => $username]);
		
		if($stmt->rowCount() > 0) {
			
			return true;
			
		} else {
			
			return false;
			
		}
		
	}
    public function getPosts($id)
    {
        $images = null;
		
        $stmt = $this->db->prepare("SELECT * FROM user_images WHERE user_id = :id ORDER BY upload_date DESC");
        $stmt->execute([':id' => $id]);
		
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
		
        foreach($result as $row) {
            $imgc = $this->db->prepare("SELECT * FROM image_likes WHERE image_id = :imgid");
            $imgc->execute([':imgid' => $row['id']]);
            $likes = $this->db->prepare("SELECT * FROM image_likes WHERE image_id = :imgid AND user_id = :uid");
            $likes->execute([':imgid' => $row['id'], ':uid' => Session::get('id')]);
            if ($likes->rowCount() == 0) {
                $likes = 0;
            } else {
                $likes = 1;
            }
            
            $images .= '
			
            <li>
                <a href="'.SITE_URL.'assets/images/'.$row['image_url'].'">
					<div class="imageBox">
						<img image_id="'.$row['id'].'" src="'.SITE_URL.'assets/images/'.$row['image_url'].'" alt="" onclick="showImage('.$row['id'].','.$likes.');">
                        <div class="infobox">
                            <span class="_likesimage" id="'.$row['id'].'">❤️️ '.$imgc->rowCount().'</span>
                        </div>
                    </div>
                </a>
            </li>';
        }	
		
        return $images;
    }
    public function getPostsCount($id)
    {
        $stmt = $this->db->prepare("SELECT COUNT(user_id) FROM user_images WHERE user_id = :id");
        $stmt->execute([':id' => $id]);
        $rowCount = (int) $stmt->fetchColumn();
        
        if($rowCount == 1) {
            return $rowCount." ".STR_PHOTO;
        }
        else
        {
            return $rowCount." ".STR_PHOTOS;
        }
        
    }
    
    public function getFollowingCount($id = false)
    {
        $stmt = $this->db->prepare("SELECT COUNT(following_id) FROM user_following WHERE user_id = :id");
        $stmt->execute([':id' => $id]);
        $rowCount = (int) $stmt->fetchColumn();
   
        return $rowCount." ".STR_FOLLOWING;
    }
    
    public function getFollowersCount($id = false)
    {
        $stmt = $this->db->prepare("SELECT COUNT(user_id) FROM user_following WHERE following_id = :id");
        $stmt->execute([':id' => $id]);
        $rowCount = (int) $stmt->fetchColumn();
        
        if($rowCount == 1) {
            return $rowCount." ".STR_FOLLOWER;
        }
        else
        {
            return $rowCount." ".STR_FOLLOWERS;
        }
    }
    public function getUserInfoByName($username = false) {
        
        $stm =  $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stm->execute([':username' => $username]);
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        
        return $result;
    }
    public function followCheck($dID, $sID)
    {
        $stmt = $this->db->prepare("SELECT COUNT(following_id) as count FROM user_following WHERE user_id = :sid AND  following_id = :id");
        $stmt->execute([':sid' => $sID, ':id' => $dID]);
        $result = $stmt->fetch();
        return ($result['count'] > 0);
    }
}// class

