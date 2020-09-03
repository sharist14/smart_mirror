<?php
require_once 'Db.php';

class Router
{
    private $routes;

    //покдлючаемся к БД
    public function __construct(){
        $Db = new Db();
        $Db->getConnect();

        $this->routes = include('routes.php');
    }
    
    public function run(){

        // Парсим УРЛ
        $way =  trim($_SERVER['REQUEST_URI'], '/');

        // Сравниваем маршруты
        foreach($this->routes as $key => $route){
            if($res = preg_match("~$key~", $way)){

                $internal_route = preg_replace("~$key~", $route, $way);
                $internal_route = explode('/', $internal_route);

                $controller_name = array_shift($internal_route).'Controller';

                $action_name = 'action' . ucfirst(array_shift($internal_route));

                $parameters = $internal_route;

                $controller_file = WWW . '/controllers/' . $controller_name . '.php';

                if( file_exists($controller_file) ){
                    require_once($controller_file);
                }

                $controller = new $controller_name();
                $controller->$action_name($parameters);

                break;
            }
        }
    }
}