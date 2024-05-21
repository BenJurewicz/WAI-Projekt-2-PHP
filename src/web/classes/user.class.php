<?php
require_once "database.php";

class User
{
    public $_id;
    public $username;
    public $email;
    public $hashedPassword;

    public function __construct($_id, $username, $email, $hashedPassword)
    {
        $this->_id = $_id;
        $this->username = $username;
        $this->email = $email;
        $this->hashedPassword = $hashedPassword;
    }

    public function checkIfCorrectPassword($password)
    {
        return password_verify($password, $this->hashedPassword);
    }

    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function logIn()
    {
        session_unset();
        $_SESSION['user'] = $this;
        $_SESSION['loggedIn'] = true;
    }

    public static function logOut()
    {
        // session_start();
        session_unset();
        session_destroy();
    }

    public static function isLoggedIn()
    {
        return isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true;
    }

    public static function getCurrentUser()
    {
        // if (session_status() == PHP_SESSION_NONE) {
        // session_start();
        // }
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }
        return null;
    }
}

class createUser extends User
{

    public function __construct($username, $email, $password)
    {
        $_id = database::createID();
        $hashedPassword = User::hashPassword($password);

        parent::__construct($_id, $username, $email, $hashedPassword);
        if ($this->savetoDB() == false) {
            throw new Exception("User creation failed");
        }
    }

    private function savetoDB()
    {
        if (database::checkIfUserExists($this->username)) {
            return false;
        }
        return database::createUser($this->toUser());
    }

    public function toUser()
    {
        return new User($this->_id, $this->username, $this->email, $this->hashedPassword);
    }
}
