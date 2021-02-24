<?php
require 'config.php';

class Uploader {

    function __construct() {
    }

    private function generateRandomString($length = 10) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }

    function uploadPhoto() {
        if (strlen($_FILES["file"]["name"]) < 5) {
            throw new Exception("Filename too short");
        }
        $ext = str_replace(".", "", substr($_FILES["file"]["name"], -4));
        $valid_ext = array('png','gif','jpg','jpeg');
        if (!in_array($ext,$valid_ext)) {
            throw new Exception("Invalid file extension");
        }
        $randdir = $this->generateRandomString(5);
        if (!file_exists(UPLOAD_DIR."/".$randdir)) {
            mkdir(UPLOAD_DIR."/".$randdir, 0777, true);
        }
        $randname = $this->generateRandomString(20);
        $relative_path = $randdir."/".$randname.".".$ext;
        $target_file = UPLOAD_DIR."/".$relative_path;
        // Check if image file is a actual image or fake image
        if (exif_imagetype($_FILES["file"]["tmp_name"]) == false) {
            throw new Exception("Invalid image");
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            throw new Exception("File already exists");
        }
        // Check file size
        if ($_FILES["file"]["size"] > (1024*1024*5)) {
            throw new Exception("File size not supported");
        }
        //Compress the image
        if (($ext == "jpg") || ($ext == "jpeg"))
            $image = imagecreatefromjpeg($_FILES["file"]["tmp_name"]);
        elseif ($ext == "gif")
            $image = imagecreatefromgif($_FILES["file"]["tmp_name"]);
        elseif ($ext == "png")
            $image = imagecreatefrompng($_FILES["file"]["tmp_name"]);

        //Resize
        list($width, $height) = getimagesize($_FILES["file"]["tmp_name"]);
        if (($width >= MAX_IMAGE_WIDTH) || ($height >= MAX_IMAGE_HEIGHT)) {
            $percent = 0.5;
            $newwidth = $width * $percent;
            $newheight = $height * $percent;
            $thumb = imagecreatetruecolor($newwidth, $newheight);
            imagecopyresized($thumb, $image, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        } else {
            $thumb = $image;
        }
        if (imagejpeg($thumb, $target_file, 90)) {
            unlink($_FILES["file"]["tmp_name"]);
            return $relative_path;
        } else {
            throw new Exception("Upload failed");
        }
    }
}

?>
