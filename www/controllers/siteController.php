<?php

class siteController
{
    public function actionMain(){
        echo "class - " . __CLASS__;
        $body = get_template('site', 'index', 'body');

        echo "<pre>";
            print_r($body);
        echo "</pre>";
        
        
    }

    public function actionTest($parameters){
        echo "<pre>";
            print_r($parameters);
        echo "</pre>";


    }
    
    
}