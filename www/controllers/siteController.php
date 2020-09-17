<?php
require_once(WWW.'/api/weather/openweathermap.php');


class siteController
{
    var $params;

    public function actionMain(){
        $body = get_template('site', 'body_content', 'body');
        $row = get_template('site', 'body_content', 'row');


        // Получаем данные погоды по api
        $weather_api = new openweathermap();
        $data_temp = $weather_api->getCurrentWeather();
        $this->params = $weather_api->getParams();

        // Заносим данные в шаблон погоды
        $body = set($body, 'weather', $data_temp);


        // Отправляем на рендер
        viewController::display($body, $this->params);
    }

    public function actionTest($parameters){

    }
    
}