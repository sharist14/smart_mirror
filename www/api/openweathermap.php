<?php

// Weather api
class openweathermap
{

    public $url_curr_weather = 'api.openweathermap.org/data/2.5/weather?';
    public $city_name = 'Saint Petersburg,RU';
    public $city_id = '498817';   //London,uk


    public function getCurrentWeather(){

        $body = get_template('site', 'weather', 'body');

//        $url = $this->url_curr_weather.'q='.$this->city_name.'&APPID='.WEATHER_CURRENT_API.'&lang=ru&units=metric';
        $url = $this->url_curr_weather.'id='.$this->city_id.'&APPID='.WEATHER_CURRENT_API.'&lang=ru&units=metric';

        // Отправляем на рендер
        $data = $this->send_query($url);

        $body = $this->renderData($body, $data);







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

    public function renderData($template, $data){

        $template = set($template, 'weather_id', $data['weather'][0]['id']);        // id погодных условий
        $template = set($template, 'main', $data['weather'][0]['main']);  //
        $template = set($template, 'desc', $data['weather'][0]['description']);  //
        $template = set($template, 'icon', $data['weather'][0]['icon']);  //
        $template = set($template, 'temp', $data['main']['temp']);  //
        $template = set($template, 'feels_like', $data['main']['feels_like']);  //
        $template = set($template, 'pressure', $data['main']['pressure']);  //
        $template = set($template, 'humidity', $data['main']['humidity']);  //
        $template = set($template, 'visibility', $data['visibility']);  //
        $template = set($template, 'wind_speed', $data['wind']['speed']);  //
        $template = set($template, 'wind_deg', $data['wind']['deg']);  //
        $template = set($template, 'rain_1h', $data['rain']['1h']);  //
        $template = set($template, 'clouds_all', $data['clouds']['all']);  //
        $template = set($template, 'dt', date('d.m.Y G:i:s', $data['dt']));  //
        $template = set($template, 'sys_sunrise', date('d.m.Y G:i:s', $data['sys']['sunrise']));  //
        $template = set($template, 'sys_sunset', date('d.m.Y G:i:s', $data['sys']['sunset']));  //
        $template = set($template, 'timezone', $data['sys']['sunset']);  //
        $template = set($template, 'city_id', $data['id']);  //
        $template = set($template, 'name', $data['name']);  //



        $icon = '<img src="http://openweathermap.org/img/wn/'.$data['weather'][0]['icon'].'@2x.png">';  // 10d.png 10d@2x.png
        $template = set($template, 'weather_icon_img', $icon);  // Иконка





        return $template;
    }
}