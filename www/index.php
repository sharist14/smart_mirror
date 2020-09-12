<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);


/* Глобальные настройки и включения */
require_once 'config/ways.php';
require_once 'config/Router.php';
include_once 'config/utils.php';
require_once('views/viewController.php');


/* Роутер */
$router = new Router();
$router->run();




?>