<?php

class RegisterModel
{
    public $usernameTaken;
    public $usernameTakenMessage;
    public $incorrectEmail;
    public $incorrectEmailMessage;
    public $incorrectUsername;
    public $incorrectUsernameMessage;
    public $incorrectPassword;
    public $incorrectPasswordMessage;
    public $incorrectRepeatPassword;
    public $incorrectRepeatPasswordMessage;

    public function __construct()
    {
        $this->usernameTaken = false;
        $this->usernameTakenMessage = "a";
        $this->incorrectEmail = false;
        $this->incorrectEmailMessage = "b";
        $this->incorrectUsername = false;
        $this->incorrectUsernameMessage = "c";
        $this->incorrectPassword = false;
        $this->incorrectPasswordMessage = "d";
        $this->incorrectRepeatPassword = false;
        $this->incorrectRepeatPasswordMessage = "e";
    }
}
