<?php

// Weather api
class openweathermap
{
    public $city = 'Saint Petersburg,RU';   //London,uk

    public function getCurrentWeather(){

        $body = get_template('site', 'weather', 'body');

        $url = 'api.openweathermap.org/data/2.5/weather?q='.$this->city.'&APPID='.WEATHER_API;

        $result = $this->send_query($url);


        pre($result);
        $body = set($body, 'city', $result['name']);

        return $body;

    }

    public function send_query($url){        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Устанавливаем параметр, чтобы curl возвращал данные, вместо того, чтобы выводить их в браузер.
        curl_setopt($ch, CURLOPT_URL, $url);

        $data = curl_exec($ch);
        curl_close($ch);

        return json_decode($data,true);
    }
}