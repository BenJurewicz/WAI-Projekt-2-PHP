<?php

class loginModel
{
    public $incorrectLoginAttempt;

    public function __construct()
    {
        $this->incorrectLoginAttempt = false;
    }
}
