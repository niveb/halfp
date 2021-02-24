<?php
/* Controller Index */

class user extends Controller {
        function __construct() {
		parent::__construct();
		Session::init();
		if(Session::get('loggedIn') == false) {
			Session::destroy();
			echo "<script>window.location.href='".APP_URL."login';</script>";  
			exit;
		}
                $this->view->js = ['functions.js', 'popup.js', 'follow.js', 'profile.js'];
                $this->view->css = ['profile.css','popup.css'];
               
	}
	
	function Index($username = false) 
	{
            if ($username === false) {
               redirect("user/".Session::get('username'));
               return;
            }

            redirect("/feed/?u=" . $username);
            return;
            /* Old stuff below */
/*
            if($this->model->checkUserExists($username) == false) {
                redirect('/');
                return;
            }

            $userid = $this->model->getID(strtolower($username));
            $this->view->selfuser = ($userid == Session::get('id'));
            $this->view->userData = $this->model->getUserInfoByID($userid);
            $this->view->myself = $this->model->getUserInfoByID(Session::get('id'));
            $this->view->getPosts = $this->model->getPosts($userid);
            
			$this->view->verified = $this->model->verified($username);
			 
            $this->view->posts = $this->model->getPostsCount($userid);
            $this->view->following = $this->model->getFollowingCount($userid);
            $this->view->followers = $this->model->getFollowersCount($userid);
            if (!$this->view->selfuser) {
                $this->view->followCheck = $this->model->followCheck($this->view->userData['id'], Session::get("id"));
            }
            $this->view->render('Profile');
*/
	}
}
