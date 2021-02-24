<?php
/* Controller Index */

class feed extends Controller {
        function __construct() {
		parent::__construct();
		Session::init();
        session_start();
		if(Session::get('loggedIn') == false) {
			Session::destroy();
			echo "<script>window.location.href='".APP_URL."login';</script>";  
			exit;
		}
                $this->view->js = ['functions.js', 'feed.js', 'follow.js', 'profile.js'];
                $this->view->css = ['profile.css'];
		
	}
	
	function Index() 
	{
            $this->view->myself = $this->userModel->getUserInfoByID(Session::get('id'));
            $this->view->showprofile = false;
            $this->view->selfuser = false;
            if (isset($_GET['u']) && ($this->userModel->checkUserExists($_GET['u']) == true)) {
                $this->view->showprofile = true;
                $username = $_GET['u'];
                $userid = $this->userModel->getID(strtolower($username));
                if ($this->model->checkBlockedUser($userid, Session::get('id'))) {
                    redirect("feed");
                }
                $this->view->selfuser = ($userid == Session::get('id'));
                $this->view->userData = $this->userModel->getUserInfoByID($userid);
                $this->view->getPosts = $this->userModel->getPosts($userid);
                $this->view->verified = $this->userModel->verified($username);
                $this->view->posts = $this->userModel->getPostsCount($userid);
                $this->view->following = $this->userModel->getFollowingCount($userid);
                $this->view->followers = $this->userModel->getFollowersCount($userid);
                $this->view->blocked = false;
                if (!$this->view->selfuser) {
                    $this->view->blocked = $this->model->checkBlockedUser(Session::get('id'), $userid);
                    $this->view->followCheck = $this->userModel->followCheck($this->view->userData['id'], Session::get("id"));
                }

            }
            $this->view->render('feed');
	}
    function reportPost() {
        if (!isset($_POST['postid']))
            return;
        $postid = $_POST['postid'];
        if (!is_numeric($postid))
            return;
        echo $this->model->report(Session::get('id'), $postid, 'post');
    }
    function reportUser() {
        if (!isset($_POST['userid']))
            return;
        $userid = $_POST['userid'];
        if (!is_numeric($userid) || (Session::get('id') == $userid))
            return;
        echo $this->model->report(Session::get('id'), $userid, 'user');
    }
    function unblockUser() {
        if (!isset($_POST['userid']))
            return;
        $userid = $_POST['userid'];
        if (!is_numeric($userid) || (Session::get('id') == $userid))
            return;
        echo $this->model->unblockUser(Session::get('id'), $userid);
    }
    function blockUser() {
        if (!isset($_POST['userid']))
            return;
        $userid = $_POST['userid'];
        if (!is_numeric($userid) || (Session::get('id') == $userid))
            return;
        echo $this->model->blockUser(Session::get('id'), $userid);
    }

    function deletePost() {
        if (!isset($_POST['postid']))
            return;
        $postid = $_POST['postid'];
        if (!is_numeric($postid))
            return;
        echo $this->model->deletePost(Session::get('id'), $postid);
    }
        function posts() 
        {
			$offset = $_GET['offset'];
            $limit  = $_GET['limit'];
            $user = $_GET['user'];
			
            $this->model->posts(Session::get('id'), Session::get('username'), $offset, $limit, $user);
        }	
        function likePost() {
             $this->model->likePost(Session::get('id'));
        }
        function unlikePost() {
             $this->model->unlikePost(Session::get('id'));
        }
        function addComment() {
             $this->model->addComment(Session::get('id'));
        }
        function removeComment() {
             $this->model->removeComment(Session::get('id'));
        }
        function search() {
             $this->model->search();
        }
        function follow(){
            $this->model->followUser(Session::get('id'));
        }
        function unfollow(){
            $this->model->unfollowUser(Session::get('id'));
        }
        function logout() 
        {
            Session::init();
            if(Session::get('loggedIn') == true) {
                    Session::destroy();
                    header('location: '.APP_URL);
                    exit;
            }
        }
}
