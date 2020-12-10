<?php
require_once(WWW.'/api/weather/weatherApi.php');


class siteController
{
    var $params;

    public function actionMain(){
        $body = get_template('site', 'body_content', 'body');


        // Получаем данные погоды по api
        $weather = new weatherApi();
        $data_temp = $weather->getWeather();
        $this->params = $weather->getParams();

        // Заносим данные в шаблон погоды
        $body = set($body, 'weather', $data_temp);


        // Отправляем на рендер
        viewController::display($body, $this->params);
    }

    public function actionTest($parameters){

    }
    
}