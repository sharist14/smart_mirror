<?php

// Weather api
class openweathermap
{

    public $url_curr_weather = 'api.openweathermap.org/data/2.5/';
    public $city_name = 'Saint Petersburg,RU';
    public $city_id = '498817';   //Saint Petersburg
    public $city_coord = 'lat=59.89&lon=30.26';
    public $wObj;
    public $waObj;


    public function getCurrentWeather(){

        $body = get_template('weather', 'weather', 'body');

        /* Current weather */
        $operation = 'weather';
        $url = $this->url_curr_weather.$operation.'?id='.$this->city_id.'&APPID='.WEATHER_CURRENT_API.'&lang=ru&units=metric';
        $data_curr = $this->send_query($url); // Запрашиваем погоду
        
        // Если данные корректные, добавляем их в объект
        if( !$data_curr['message']){
            require_once(WWW.'/api/weather/weatherObj.php');
            $this->wObj = new weatherObj($data_curr);
        } else{
            die('Не удалось получить корректные данные о погоде по api');
        }
        

        /* One call API */
        $operation = 'onecall';
        $url = $this->url_curr_weather.$operation.'?'.$this->city_coord.'&appid='.WEATHER_CURRENT_API;
        $data_all = $this->send_query($url); // Запрашиваем погоду

        // Если данные корректные, добавляем их в объект
        if( !$data_all['message']){
            require_once(WWW.'/api/weather/weatherAllObj.php');
            $this->waObj = new weatherAllObj($data_all);
        } else{
            die('Не удалось получить корректные данные о погоде по api');
        }

        


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


    public function renderData($template){

        // Вставляем из класса все данные полученные из api
        foreach($this->wObj as $key => $value){
            $template = set($template, $key, $value );
        }




        // Определяем время суток
        $cur_date = date('H:i:s', time());
        if( ($cur_date > $this->wObj->sunrise) && ($cur_date < $this->wObj->sunset)){
//            pre('Сейчас светло');
        } else{
//            pre('Сейчас темно');
        }


        // Главная иконка
        $icon = '<img src="http://openweathermap.org/img/wn/'.$this->wObj->w_icon.'@2x.png">';
        $template = set($template, 'weather_icon_img', $icon);



        return $template;
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