<?php
/* Controller Index */

class contactus extends Controller {
        function __construct() {
		parent::__construct();
		Session::init();
		if(Session::get('loggedIn') == false) {
			Session::destroy();
			echo "<script>window.location.href='".APP_URL."login';</script>";
			exit;
		}
		$this->view->css = ['contactus.css'];
        $this->view->js = ['functions.js', 'contactus.js'];
		
	}
	
	function Index() 
	{
            $this->view->userData = $this->userModel->getUserInfoByID(Session::get('id'));
			$this->view->myself = $this->view->userData;
			
            $this->view->render('contactus');
	}

    function sendEmail() {
        $userData = $this->userModel->getUserInfoByID(Session::get('id'));
        echo $this->model->sendEmail($userData['username'], $userData['email'], $_POST['message']);
    }
	
}
