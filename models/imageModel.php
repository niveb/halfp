<?php

class Comment {
	public $text;
	public $author_name;
	public $author_id;
	public $image_id;
}

class imageModel extends Model {
	
    function __construct() {
        parent::__construct();
    }
    public function getComments($id) {
        $comments = $this->db->prepare("SELECT * FROM image_comments WHERE image_id = :imgid");
        $comments->execute([':imgid' => $id]);
		$ret = array();
        foreach($comments->fetchAll(PDO::FETCH_ASSOC) as $com) {
            $usercomName =  $this->db->prepare("SELECT * FROM users WHERE id = :comID");
            $usercomName->execute([':comID' => $com['user_id']]);
            $name = $usercomName->fetch(PDO::FETCH_ASSOC);
			$c = new Comment();
			$c->text = $com['image_comment'];
			$c->author_id = $com['user_id'];
			$c->image_id = $id;
			$c->author_name = $name['username'];
			array_push($ret, $c);
         }
		return $ret;
    }

}
