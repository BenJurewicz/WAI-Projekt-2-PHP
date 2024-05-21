<?php

// require all the controlers
foreach (glob("controllers/*.php") as $filename) {
    require_once $filename;
}

// require all the models
foreach (glob("models/*.php") as $filename) {
    require_once $filename;
}

require_once "database.php";
require_once "views/view.php";
require_once "globals.php";


class Router
{
    public $routes = [
        "/" => GalleryController::class,
        "/upload" => UploadController::class,
        "/register" => RegisterController::class,
        "/logout" => LogoutController::class,
        "/login" => LoginController::class,
    ];

    private function getController($path)
    {
        foreach ($this->routes as $route => $controller) {
            if ($route == $path) {
                return $controller;
            }
        }
    }

    public function route($path)
    {
        $controller = $this->getController($path);
        if ($controller != null) {
            $controller = new $controller();

            session_start();

            $view = $controller->get();
            $view->render();
        } else {
            require_once "views/404View.php";
        }
    }
}
