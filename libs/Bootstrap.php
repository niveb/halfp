<?php
/* Controller Index */

class Bootstrap {
    function __construct() {
        if (!isset($_GET['url'])) {
            header("Location: " . APP_URL);
            return;
        }
        $url = $_GET['url'];
        $url = rtrim($url, '/');
        $url = explode('/', $url);

        if(empty($url[0])) {
            require 'controllers/login.php';
            $controller = new login();
            $controller->Index();
            return;
        }

        $file = 'controllers/'.$url[0].'.php';

        if (!file_exists($file)) {
            $this->error();
            return;
        }

        require $file;
        $controller = new $url[0];
        $controller->loadModel($url[0]);
        // calling methods
        if (isset($url[2])) {
            if (method_exists($controller, $url[1])) {
                $controller->{$url[1]}($url[2]);
            } else {
                $this->error();
            }
        } else {
            if (isset($url[1])) {
                if (method_exists($controller, $url[1])) {
                    $controller->{$url[1]}();
                } elseif ($url['0'] === "user"){
                    $controller->index($url[1]);
                } else {
                    $this->error();
                }
            } else {
                $controller->index();
            }
        }
    }

    function error() {
        require 'controllers/error.php';
        $controller = new ErrorController();
        $controller->showError(STR_NOTFOUND);
        return;
    }
}
