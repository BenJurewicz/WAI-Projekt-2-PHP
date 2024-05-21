<?php
require_once "database.php";
require_once "views/view.php";
require_once "controller.php";
require_once "classes/image.class.php";
require_once "classes/user.class.php";

require_once "models/registerModel.php";

class RegisterController implements Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new RegisterModel();
    }

    public function get()
    {
        // session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->isCorrectPostRequest()) {
                $user = new CreateUser($_POST['username'], $_POST['email'], $_POST['password']);
                $user = $user->toUser();
                $user->logIn();
                header("Location: /");
            }
        }

        return new View("register", $this->model);
    }

    private function isCorrectPostRequest()
    {
        $isCorrectEmail = $this->isCorrectEmail();
        $isCorrectUsername = $this->isCorrectUsername();
        $isUserNameUnique = $this->isUserNameUnique();
        $isCorrectPassword = $this->isCorrectPassword();
        $isCorrectPassword2 = $this->isCorrectPassword2();


        return $isCorrectEmail &&
            $isCorrectUsername &&
            $isUserNameUnique &&
            $isCorrectPassword &&
            $isCorrectPassword2;
    }

    private function isCorrectEmail()
    {
        // the not equal false is here becasue I want the function to return a boolean
        // and filter_var returns parameter value or boolean
        $check =  isset($_POST['email']) && (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) != false);
        if (!$check) {
            $this->model->incorrectEmail = true;
            $this->model->incorrectEmailMessage = "Email is not in a correct format";
        }
        return $check;
    }

    private function isCorrectUsername()
    {
        $check = isset($_POST['username']) && preg_match('/^[a-zA-Z0-9\-_ ]{2,100}$/', $_POST['username']);
        if (!$check) {
            $this->model->incorrectUsername = true;
            $this->model->incorrectUsernameMessage = "Username must be 2-100 letters long and contain only letters, numbers, hyphens (-), underscores and spaces";
        }

        return $check;
    }

    private function isCorrectPassword()
    {
        $check = isset($_POST['password']) && preg_match('/^.{1,100}$/', $_POST['password']);
        if (!$check) {
            $this->model->incorrectPassword = true;
            $this->model->incorrectPasswordMessage = "Password must be 2-100 characters long";
        }
        return $check;
    }

    private function isCorrectPassword2()
    {
        // passwordHash exits because we check for it before calling this function
        $check = isset($_POST['password2']) && $_POST['password'] == $_POST['password2'];
        if (!$check) {
            $this->model->incorrectRepeatPassword = true;
            $this->model->incorrectRepeatPasswordMessage = "Passwords do not match";
        }
        return $check;
    }

    private function isUserNameUnique()
    {
        if (!isset($_POST['username'])) {
            return false;
        }

        $check = !(database::checkIfUserExists($_POST['username']));
        if (!$check) {
            $this->model->usernameTaken = true;
            $this->model->usernameTakenMessage = "Username is already taken";
        }
        return $check;
    }
}
