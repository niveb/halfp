<?php

class Controller {
	
	function __construct() {	
		$this->view = new View();
	}
	
	public function loadModel($name) {
		$path ='models/'.$name.'Model.php';

		if(file_exists($path)) {
            $usermodelpath = 'models/userModel.php';
            if ($usermodelpath != $path) {
			    require 'models/userModel.php';
			    $this->userModel = new userModel;
            }
			require $path;
			$modelName = $name . 'Model';
			$this->model = new $modelName;
		} else {
            echo "File $path does not exist";
        }
	}
	
}
