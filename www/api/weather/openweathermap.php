<?php

// Weather api
class openweathermap
{

    public $url_curr_weather = 'api.openweathermap.org/data/2.5/weather?';
    public $city_name = 'Saint Petersburg,RU';
    public $city_id = '498817';   //Saint Petersburg
    public $wObj;


    public function getCurrentWeather(){

        $body = get_template('weather', 'weather', 'body');

        $url = $this->url_curr_weather.'id='.$this->city_id.'&APPID='.WEATHER_CURRENT_API.'&lang=ru&units=metric';

        // Запрашиваем погоду
        $this->send_query($url);


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

        // Если данные корректные, добавляем их в объект
        if($data['cod'] == 200){
            require_once(WWW.'/api/weather/weatherObj.php');
            $this->wObj = new weatherObj($data);
            return true;
        } else{
            die('Не удалось получить корректные данные о погоде по api');
        }
    }

    public function renderData($template){

        pre($this->wObj);
        
        foreach($this->wObj as $key => $value){
            if($key == 'w_desc'){
                $template = set($template, $key, ucfirst_utf8($value) );
            }
            $template = set($template, $key, $value );
        }

        $icon = '<img src="http://openweathermap.org/img/wn/'.$this->wObj->w_icon.'.png">';
//        $icon = '<img src="http://openweathermap.org/img/wn/'.$this->wObj->w_icon.'@2x.png">';
        $template = set($template, 'weather_icon_img', $icon);

        $template = set($template, 'humidity_icon', '/sources/img/thermometer.png');


        return $template;
    }
}