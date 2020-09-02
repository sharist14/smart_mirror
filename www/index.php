<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);


/* Глобальные настройки и включения */
require_once 'config/ways.php';
require_once 'config/Router.php';


/* Роутер */
$router = new Router();
$router->run();




?>