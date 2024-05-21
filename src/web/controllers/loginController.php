<?php


require_once "database.php";
require_once "views/view.php";
require_once "controller.php";
require_once "classes/user.class.php";

require_once "models/loginModel.php";

class LoginController implements Controller
{
    private $model;
    private $user;

    public function __construct()
    {
        $this->model = new loginModel();
    }

    public function get()
    {
        // session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->isValidRequest()) {
                if ($this->tryToLogIn()) {
                    header("Location: /");
                } else {
                    $this->model->incorrectLoginAttempt = true;
                }
            }
        }


        return new View("login", $this->model);
    }

    private function isValidRequest()
    {
        return isset($_POST['username']) && isset($_POST['password']);
    }

    private function tryToLogIn()
    {
        $this->user = database::getUserByUsername($_POST['username']);
        if ($this->user == null) {
            return false;
        }
        if ($this->user->checkIfCorrectPassword($_POST['password'])) {
            $this->user->logIn();
            return true;
        }

        return false;
    }
}
