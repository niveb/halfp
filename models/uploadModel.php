<?php 
class uploadModel extends Model {
	
    function __construct() {
        parent::__construct();
    }

    public function upload($sID, $uploaddir = "posts")
    {
		if(isset($_POST['submit']) && !empty($_FILES['img']['name'])) {
        $description = (trim(nl2br(htmlentities($_POST['desc']))));
        if (strlen($description) > 250)
            $description = substr($description, 0, 250);

        $type =  strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));

        if(in_array($type, array("jpg","jpeg","gif","png")))
        {
            if($_FILES['img']['size'] < 5000000)
            {
                if(is_uploaded_file($_FILES['img']['tmp_name']))
                {
                    $basedir = "assets/images/".$uploaddir."/";
                    $randdir = generateRandomString(5);
                    if (!file_exists($basedir . $randdir)) {
                        mkdir($basedir . $randdir, 0777, true);
                    }
                    $randname = generateRandomString(20);
                    $relative_path = $basedir . $randdir."/".$randname.".".$type;
                    move_uploaded_file($_FILES['img']['tmp_name'],$relative_path);
                    $stm = $this->db->prepare("INSERT INTO user_images (user_id, image_url, upload_date, image_description) VALUES (:id, :imageUrl, UNIX_TIMESTAMP(), :imageDesc)");
                    $stm->execute([
                        ':id' => $sID,
                        ':imageUrl' => $randdir . "/" . $randname . "." . $type,
                        ':imageDesc' => $description
                    ]);
                    header('location: '.APP_URL.'feed');
                    return $randdir . "/" . $randname . "." . $type;

                } 
                else 
                {	
                    return STR_UPLOADFAILED;
                }
            } 
            else 
            {	
                return STR_UPLOADSIZEEXCEED;
            } 
        }
        else 
        {
            return STR_UPLOADINVALIDFILE;
        }
    } 
    }
}// class
