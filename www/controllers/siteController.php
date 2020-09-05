<?php


class siteController
{

    public function actionMain(){
        $body = get_template('site', 'index', 'body');
        $row = get_template('site', 'index', 'row');

        $body = set($body, 'order_id', 12456);
        $body = setm($body, 'order_id', 12456);

        // Отправляем на рендер
        viewController::display($body);
    }

    public function actionTest($parameters){

    }
    
}