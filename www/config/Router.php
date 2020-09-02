<?php
require_once 'Db.php';

class Router
{
    //покдлючаемся к БД
    public function __construct(){
        $Db = new Db();
        $Db->getConnect();
    }
    
    public function run(){
        $curr_url = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        echo "<pre>";
            print_r($curr_url);
        echo "</pre>";
        
        
    }
}