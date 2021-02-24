<?php
/* Controller Index */

class image extends Controller {
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
	
	function comments($id) 
	{
        $com = $this->model->getComments($id);
        $this->view->comments = $com;
        $this->view->render('imagecomments',true);
	}
}
