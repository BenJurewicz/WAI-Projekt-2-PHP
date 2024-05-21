<?php

require "router.php";

$router = new Router();
$router->route($_REQUEST["action"]);
