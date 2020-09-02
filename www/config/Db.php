<?php

require_once ROOT . '/accounts.php';

class Db
{

    public function getConnect(){
        $mysqli = new mysqli(HOST, USER, PASS, DB);

        if( !mysqli_connect_errno() ){
            return $mysqli;
        } else{
            die("Connect failed: " . mysqli_connect_error());
        }
    }
}