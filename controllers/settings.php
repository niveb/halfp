<?php
/* Controller Index */

class settings extends Controller {
        function __construct() {
		parent::__construct();
		Session::init();
		if(Session::get('loggedIn') == false) {
			Session::destroy();
			echo "<script>window.location.href='".APP_URL."login';</script>";  
			exit;
		}
		$this->view->css = ['profile.css'];
        $this->view->js = ['functions.js', 'settings.js'];
		
	}
	
	function Index() 
	{
            $this->view->userData = $this->userModel->getUserInfoByID(Session::get('id'));
			$this->view->myself = $this->view->userData;
			$this->view->messages = $this->model->settingsEdit(Session::get('id'));
			
            $this->view->render('settings');
	}

    function deleteMe() {
        $this->model->deleteUser(Session::get('id'));
        $this->logout();
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
