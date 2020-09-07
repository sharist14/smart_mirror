<?php
require_once(WWW.'/api/openweathermap.php');


class siteController
{

    public function actionMain(){
        $body = get_template('site', 'index', 'body');
        $row = get_template('site', 'index', 'row');

        // Погода
        $weather = new openweathermap();
        $data_temp = $weather->getCurrentWeather();
        $body = set($body, 'weather', $data_temp);

        // Отправляем на рендер
        viewController::display($body);
    }

    public function actionTest($parameters){

    }
    
}