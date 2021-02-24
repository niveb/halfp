<?php
/* Controller Index */

class ErrorController extends Controller {
	function __construct() {
		parent::__construct();
	}
	function showError($msg) {
        $this->view->msg = $msg;
        $this->view->css = ['error.css'];
		$this->view->render('error',true);
	}
}
