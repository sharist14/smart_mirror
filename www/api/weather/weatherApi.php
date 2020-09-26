<?php

require_once(WWW.'/api/weather/weatherObj.php');

// Weather api
class weatherApi
{

    public $url_curr_weather = 'api.openweathermap.org/data/2.5/';
    public $city_name = 'Saint Petersburg,RU';
    public $city_id = '498817';                     //Saint Petersburg
    public $city_coord = 'lat=59.89&lon=30.26';
    public $wObj;                                   // Объект с готовыми данными



    public function getWeather(){

        $body = get_template('weather', 'weather', 'body');
        

        /* Подгатавливаем запрос к получению данных */
        $operation = 'onecall';
        $url = $this->url_curr_weather.$operation.'?'.$this->city_coord.'&appid='.WEATHER_CURRENT_API.'&lang=ru&units=metric';

        // Запрашиваем погоду
        $data_api = $this->send_query($url);

        // Если данные корректные, добавляем их в объект
        (!$data_api['message'])? $this->wObj = new weatherObj($data_api) : die('Не удалось получить корректные данные о погоде по api');


        // Отправляем на рендер
        $body = $this->renderData($body);


        return $body;
    }


    public function send_query($url){
        $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Устанавливаем параметр, чтобы curl возвращал данные, вместо того, чтобы выводить их в браузер.
            curl_setopt($ch, CURLOPT_URL, $url);

            $data = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($data,true);

        return $data;
    }


    public function renderData($body){

        $icon = '<img src="http://openweathermap.org/img/wn/';

        /* БЛОК ТЕКУЩЕЙ ПОГОДЫ */
        foreach($this->wObj->current as $title => $value){
            $body = set($body, $title, $value );
        }

        // главная иконка
        $curr_icon = $icon.$this->wObj->current['curr_icon'].'@2x.png">';
        $body = set($body, 'weather_icon_img', $curr_icon);



        /* БЛОК ПОГОДЫ ПО ДНЯМ */

        $row_day = get_template('weather', 'weather', 'row_day');

        pre($this->wObj->daily);
        foreach($this->wObj->daily as $datetime => $arr){
            pre(date('d.m.Y', $datetime));

            if(date('Ymd', $datetime) > date('Ymd')) {

                $name_day = date('w', $datetime);
                $date = date('d.m', $datetime);

                $body = set($body, 'date_' . $name_day, $date);
                $body = set($body, 'temp_' . $name_day, tf($arr['day_temp']));

                $curr_icon = $icon . $arr['day_w_icon'] . '.png">';
                $body = set($body, 'icon_' . $name_day, $curr_icon);
            }
        }


        return $body;
    }


    public function getParams(){

        $params['style'][] = '<link rel="stylesheet" href="/sources/css/weather_animated_icons.css">';
        $params['style'][] = '<link rel="stylesheet" href="/sources/css/weather-icons.css">';
        $params['style'][] = '<link rel="stylesheet" href="/sources/css/weather-icons-wind.css">';
        $params['style'][] = '<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">';
        $params['style'][] = '<link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300&display=swap" rel="stylesheet">';
        $params['style'][] = '<link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">';
        $params['style'][] = '<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">';

        return $params;
    }
}