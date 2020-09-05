<?php

class siteController
{
    public function actionMain(){
//        echo "class - " . __CLASS__;
        $body = get_template('site', 'index', 'body');
        $row = get_template('site', 'index', 'row');



    }

    public function actionTest($parameters){


    }
    
    
}