<?php 
class settingsModel extends Model {
	
	function __construct() {
            parent::__construct();
	}

    public function deleteUser($sID) {
	    $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :sid");
	    $stmt->execute([':sid' => $sID]);
        if ($stmt->rowCount() > 0) {
            $res = $stmt->fetch();
            // Delete profile image
            if ($res['profile_image'] != DEFAULT_PROFILE_IMAGE)
                unlink("assets/images/profile/".$res['profile_image']);
            // Cascade deletion managed by db
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = :sid");
            $stmt->execute([':sid' => $sID]);
        }
    }
	
	public function settingsEdit($sID)
    {
		if(isset($_POST['submit'])) {
            if (isset($_POST['searchable'])) {
                $stmt = $this->db->prepare("UPDATE users SET searchable = 1 WHERE id = :sid");
                $stmt->execute([':sid' => $sID]);
            } else {
                $stmt = $this->db->prepare("UPDATE users SET searchable = 0 WHERE id = :sid");
                $stmt->execute([':sid' => $sID]);
            }
            if (isset($_POST['bio'])) {
                $bio = htmlentities($_POST['bio']);
                if (strlen($bio) > 250)
                    $bio = substr($bio, 0, 250);
                if($bio != $row['profile_bio']) {
                    $stmt = $this->db->prepare("UPDATE users SET profile_bio = :bio WHERE id = :sid");
                    $stmt->execute([':bio' => $bio, ':sid' => $sID]);
                }
            }
            if (empty($_FILES['img']['name'])) {
                return;
            }
	        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :sid");
	        $stmt->execute([':sid' => $sID]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $basedir = "assets/images/profile/";
            //Delete old profile photo
            if ($row["profile_image"] != DEFAULT_PROFILE_IMAGE) {
                unlink($basedir . $row["profile_image"]);
            }
            $type =  strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));

            if(in_array($type, array("jpg","jpeg","gif","png")))
            {
                if($_FILES['img']['size'] < 5000000)
                {
                    if(is_uploaded_file($_FILES['img']['tmp_name']))
                    {
                        $randdir = generateRandomString(5);
                        if (!file_exists($basedir . $randdir)) {
                            mkdir($basedir . $randdir, 0777, true);
                        }
                        $randname = generateRandomString(20);
                        $relative_path = $basedir . $randdir."/".$randname.".".$type;
                        move_uploaded_file($_FILES['img']['tmp_name'], $relative_path);
                        //Update profile photo
                        $stmt = $this->db->prepare("UPDATE users SET profile_image = :image WHERE id = :sid");
                        $stmt->execute([':image' => $randdir."/".$randname.".".$type, ':sid' => $sID]);
                        redirect("user/".Session::get('username'));
                    } else {
                        return STR_UPLOADFAILED;
                    }
                } else {
                    return STR_UPLOADSIZEEXCEED;
                }
            }
            else {
                return STR_UPLOADINVALIDFILE;
            }
        }
    }
}
