<?php

class weatherObj
{
    public $coord;
    public $w_id;
    public $w_main;
    public $w_desc;
    public $w_icon;
    public $base;
    public $temp;
    public $temp_like;
    public $temp_min;
    public $temp_max;
    public $pressure;
    public $humidity;
    public $visibility;
    public $wind_speed;
    public $wind_deg;
    public $wind_gust;
    public $clouds_all;
    public $update_date;
    public $sunrise;
    public $sunset;
    public $country;
    public $timezone;
    public $city_id;
    public $city_name;
    public $code_query;


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
        $this->wind_gust = $data['wind']['gust'];
        $this->clouds_all = $data['clouds']['all'];
        $this->update_date = df($data['dt'], 'f');
        $this->sunrise = df($data['sys']['sunrise'],'f');
        $this->sunset = df($data['sys']['sunset'], 'f');
        $this->country = $data['sys']['country'];
        $this->timezone = $data['timezone'];
        $this->city_id = $data['id'];
        $this->city_name = $data['name'];
        $this->code_query = $data['cod'];
    }


}