<?php
require_once "database.php";
require_once "views/view.php";
require_once "controller.php";

require_once "classes/user.class.php";

class logoutController implements Controller
{
    public function get()
    {
        User::logOut();
        header("Location: /");
    }
}
