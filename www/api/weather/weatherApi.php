<?php

require_once(WWW.'/api/weather/weatherObj.php');


class weatherApi
{

    public $url_curr_weather = 'https://api.openweathermap.org/data/2.5/';
    public $city_name = 'Saint Petersburg,RU';      // title city (Saint-Petersburg)
    public $city_id = '498817';                     // city id (Saint-Petersburg)
    public $city_coord = 'lat=59.89&lon=30.26';     // city coordinate (Saint-Petersburg)
    public $wObj;                                   // Obj with data from API
    public $hour_chart;                             // chart for hour forecast



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

    // TODO настроить размер шрифта для ПК и мобильной версии
    public function renderData($body){
        
        /******* БЛОК ТЕКУЩЕЙ ПОГОДЫ *******/

        foreach($this->wObj->current as $title => $value){
            $body = set($body, $title, $value );
        }

        /*Иконка от weather api
        $curr_icon = $icon.$this->wObj->current['curr_icon'].'@2x.png">';
        $body = set($body, 'weather_icon_img', $curr_icon);*/

        // Получаем видео иконку
        $icon_name = getNameWeatherIcon($this->wObj->current);

        //Добавляем видео иконку
        $body = set($body, 'video_icon', 'sources/icon/'.$icon_name.'.mp4');

        // TODO вывести количество осадков (берем данные за ближайшие 15 минут и выводим в виде капелек)
        // Если есть осадки
        if( max($this->wObj->rain) > 0 ){
            foreach($this->wObj->rain as $datetime => $precipitation){
                pre( df($datetime, 'st') .' - '. $precipitation . ' мм.' );
            }
        }

        /******* конец БЛОКА ТЕКУЩЕЙ ПОГОДЫ *******/



        /******* БЛОК ПОГОДЫ ПОЧАСОВОЙ на 48 часов *******/

        foreach($this->wObj->hourly as $datetime => $arr){

            // Берем только данные за текущий день
            if(date('Ymd',$datetime) == date('Ymd')){
                $hour = date('H', $datetime);

                $hour_temp = round($arr["hour_temp"]);
                $this->hour_chart .= '[{v: ['.$hour.', 0, 0], f: "'.$hour.':00"},   '.$hour_temp.', "'.$hour_temp.'"],';
            }
        }

        /******* конец БЛОК ПОГОДЫ ПОЧАСОВОЙ на 48 часов *******/



        /******* БЛОК ПОГОДЫ ПО ДНЯМ *******/

        $day_row = get_template('weather', 'weather', 'day_row');

        foreach($this->wObj->daily as $datetime => $arr){

            $tt = $day_row;

            $num_day = date('w', $datetime);
            $name_day = day_of_week($num_day, 'ru_short');    // День недели
            $date = date('d.m', $datetime);                   // Дата в формате "14.02"

            $tt = set($tt, 'day_of_weak', $name_day);
            $tt = set($tt, 'date', $date);
            $tt = set($tt, 'temp',  tf($arr['day_temp']));

            $icon = '<img src="http://openweathermap.org/img/wn/';
            $curr_icon = $icon . $arr['day_w_icon'] . '.png">';
            $tt = set($tt, 'icon', $curr_icon);

            // Выделяем сб и вс
            if($num_day == 6 || $num_day == 0) {
                $tt = set($tt, 'selected', 'selected_day');
            }

            // Добавляем все даты
            $body = setm($body, 'days_rows', $tt);
        }

        /******* конец БЛОК ПОГОДЫ ПО ДНЯМ *******/


        return $body;
    }


    public function getParams(){

        $params['head'][] = '<link rel="stylesheet" href="/sources/css/weather_animated_icons.css">';
        $params['head'][] = '<link rel="stylesheet" href="/sources/css/weather-icons.css">';
        $params['head'][] = '<link rel="stylesheet" href="/sources/css/weather-icons-wind.css">';
        $params['head'][] = '<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">';
        $params['head'][] = '<link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300&display=swap" rel="stylesheet">';
        $params['head'][] = '<link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">';
        $params['head'][] = '<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">';

        /*Библиотека для построения часового графика*/

        // TODO настроить отображение температуры и не отображать по пол часа на графике

        $params['head'][] = '<script src="https://www.gstatic.com/charts/loader.js"></script>';
        $params['head'][] = '<script>
            google.charts.load("current", {packages: ["corechart", "bar"]});
            google.charts.setOnLoadCallback(drawAnnotations);
        
            function drawAnnotations() {
                var data = new google.visualization.DataTable();
                
                data.addColumn("timeofday", "");
                data.addColumn("number", "");
                data.addColumn({type: "string", role: "annotation"});
                
                data.addRows(['.$this->hour_chart. ']);
        
                var options = {
                    title: "Температура в течении дня",
                    chartArea: {
                      left: 10,
                      width: "100%"                       
                    },                    
                    annotations: {
                        alwaysOutside: false,
                      textStyle: {
                            fontSize: 14,
                        color: "#ffffff",
//                        auraColor: "none"
//                        auraColor: "#063d00"
                      }
                    },
                    hAxis: {
                      /*title: "Time of Day",*/
                      format: "H:mm",
                      viewWindow: {
                        min: [7, 30, 0],
                        max: [23, 30, 0]                                            
                      },
                      gridlines: {
                            color: "transparent"
                        },
                    },
                    vAxis: {
                        /*title: "Rating (scale of 1-10)"*/
                        gridlines: {
                            color: "transparent"
                        },
                        textPosition: "none"
                    },
                    backgroundColor: "#060406",
                    legend: {position: "none"},  
                };
            
                var chart = new google.visualization.ColumnChart(document.getElementById("chart_div"));
                chart.draw(data, options);
            }
    </script>';


        return $params;
    }



}