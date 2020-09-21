<?php

class weatherObj
{
    public $city_id;        // id города
    public $city_name;      // название города
    public $coord;          // координаты
    public $w_id;           // id текущей погоды
    public $w_main;         // группа погодных условий
    public $w_desc;         // описание текущей погоды
    public $w_icon;         // id иконки
    public $base;           // на основе чего погода (станция)
    public $temp;           // температура
    public $temp_like;      // ощущается как
    public $temp_min;
    public $temp_max;
    public $pressure;       // давление в гПа
    public $humidity;       // влажность в %
    public $visibility;     // видимость в метрах
    public $wind_speed;     // скорость ветра
    public $wind_deg;       // направление ветра, градусы (метеорологические)
    public $wind_gust;      // порыв ветра (НЕТ ДАННЫХ)
    public $clouds_all;     // Облачность в %
    public $update_date;    // Время расчета данных, unix
    public $sunrise;        // Рассвет
    public $sunrise_orig;   // Рассвет по умолч
    public $sunset;         // Закат
    public $sunset_orig;    // Закат по умолч
    public $country;        // Страна
    public $timezone;       // Временная зона (Сдвиг в секундах от UTC)

    public $code_query;     // Служебный код


    public function __construct($data){
        $this->coord = $data['coord']['lat'].','.$data['coord']['lon'];
        $this->w_id = $data['weather'][0]['id'];
        $this->w_main = $data['weather'][0]['main'];
        $this->w_desc = $data['weather'][0]['description'];
        $this->w_icon = $data['weather'][0]['icon'];
        $this->base = $data['base'];
        $this->temp = tf(round($data['main']['temp']));
        $this->temp_like = tf(round($data['main']['feels_like']));
        $this->temp_min = tf(round($data['main']['temp_min']));
        $this->temp_max = tf(round($data['main']['temp_max']));
        $this->pressure = $data['main']['pressure'];
        $this->humidity = $data['main']['humidity'];
        $this->visibility = $data['visibility'];
        $this->wind_speed = $data['wind']['speed'];
        $this->wind_deg = $data['wind']['deg'];
        $this->wind_arrow = wind_arrow($data['wind']['deg']);
        $this->wind_gust = $data['wind']['gust'];
        $this->clouds_all = $data['clouds']['all'];
        $this->update_date = df($data['dt'], 'f');
        $this->sunrise = df($data['sys']['sunrise'],'t');
        $this->sunrise_orig = $data['sys']['sunrise'];
        $this->sunset = df($data['sys']['sunset'], 't');
        $this->sunset_orig = $data['sys']['sunset'];
        $this->country = $data['sys']['country'];
        $this->timezone = $data['timezone'];
        $this->city_id = $data['id'];
        $this->city_name = $data['name'];
        $this->code_query = $data['cod'];
    }


}