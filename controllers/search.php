<?php
/* Controller Index */

class search extends Controller {
        function __construct() {
        parent::__construct();
            Session::init();
            if(Session::get('loggedIn') == false) {
                Session::destroy();
                echo "<script>window.location.href='".APP_URL."login';</script>";
                exit;
            }
            $this->view->js = ['functions.js', 'search.js'];
            $this->view->css = ['search.css'];
    }

    function Index()
    {
            $this->view->myself = $this->userModel->getUserInfoByID(Session::get('id'));
            if (isset($_POST['gender']) && ($_POST['gender'] == $this->view->myself['gender']))
                $gender = "same";
            else
                $gender = "opposite";
            if (isset($_POST['city'])) {
                $city = trim(strtolower($_POST['city']));
                $country = $this->view->myself['country'];
            }
            if (isset($_POST['fromage']) && isset($_POST['toage']) && is_numeric($_POST['fromage']) and is_numeric($_POST['toage'])) {
                $fromage = $_POST['fromage'];
                $toage = $_POST['toage'];
            }
            $withphoto = isset($_POST['withphoto']);
            $this->view->users = $this->model->getUsers($this->view->myself['gender'], $gender, $country, $city, $fromage, $toage, $withphoto);
            $this->view->render('search');
    }
}
