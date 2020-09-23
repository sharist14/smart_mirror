<?php

class weatherObj
{
    public $data;               // Полученные от api данные
    public $weather_arr;        // Массив с обработанными данными


    public function __construct($data){
        $this->data = $data;

        $this->weather_arr['common'] = $this->common();         // Общие данные
        $this->weather_arr['current'] = $this->current();       // Текущая погода
        $this->weather_arr['minutely'] = $this->minutely();     // Поминутный объем осадков мм на последующие 60 минут
        $this->weather_arr['hourly'] = $this->hourly();         // Почасовой прогноз на ближайшие 48 часов
        $this->weather_arr['daily'] = $this->daily();         // Почасовой прогноз на ближайшие 48 часов
    }

    /* Общие данные */
    public function common(){
        return [
            'comm_coord' => $this->data['lat'].','.$this->data['lon'],
            'comm_timezone' => $this->data['timezone'],
            'comm_timezone_offset' => $this->data['timezone_offset'],
        ];
    }

    /* Текущая погода */
    public function current(){
        return [
            'curr_update_date' => $this->data['current']['dt'],                         // Время расчета данных, unix
            'curr_sunrise' => $this->data['current']['sunrise'],                        // Рассвет
            'curr_sunset' => $this->data['current']['sunset'],                          // Закат
            'curr_temp' => $this->data['current']['temp'],                              // температура
            'curr_feels_like' => $this->data['current']['feels_like'],                  // ощущается как
            'curr_pressure' => $this->data['current']['pressure'],                      // давление в гПа
            'curr_humidity' => $this->data['current']['humidity'],                      // влажность в %
            'curr_dew_point' => $this->data['current']['dew_point'],                    // Атмосферная температура (меняется в зависимости от давления и влажности), ниже которой капли воды начинают конденсироваться и может образовываться роса. Единицы в метрическая система: Цельсий
            'curr_uvi' => $this->data['current']['uvi'],                                // Полуденный ультрафиолетовый индекс
            'curr_clouds' => $this->data['current']['clouds'],                          // Облачность в %
            'curr_visibility' => $this->data['current']['visibility'],                  // видимость в метрах
            'curr_wind_speed' => $this->data['current']['wind_speed'],                  // скорость ветра
            'curr_wind_deg' => $this->data['current']['wind_deg'],                      // направление ветра, градусы (метеорологические)
            'curr_w_id' => $this->data['current']['weather'][0]['id'],                  // id текущей погоды
            'curr_w_main' => $this->data['current']['weather'][0]['main'],            // группа погодных условий
            'curr_description' => $this->data['current']['weather'][0]['description'],  // описание текущей погоды
            'curr_icon' => $this->data['current']['weather'][0]['icon'],                // id иконки

            'curr_wind_gust' => $this->data['current']['wind_gust'],                    // порыв ветра метр/сек (когда доступно)
            'curr_rain_1h' => $this->data['current']['rain'][0]['1h'],                  // Объем дождя за последний час, мм (когда доступно)
            'curr_snow_1h' => $this->data['current']['snow'][0]['1h'],                  // Объем снега за последний час, мм (когда доступно)
        ];
    }

    /* Поминутный объем осадков на последующие 60 минут */
    public function minutely(){

        foreach($this->data['minutely'] as $arr){
            $minutely[$arr['dt']] = $arr['precipitation'];
        }

        return $minutely;
    }

    /* Почасовой прогноз на ближайшие 48 часов */
    public function hourly(){
        foreach($this->data['hourly'] as $data){
            $dt = $data['dt'];
            $hour[$dt]['hour_temp'] = $data['temp'];                                // температура
            $hour[$dt]['hour_feels_like'] = $data['feels_like'];                    // ощущается как
            $hour[$dt]['hour_pressure'] = $data['pressure'];                        // давление в гПа
            $hour[$dt]['hour_humidity'] = $data['humidity'];                        // влажность в %
            $hour[$dt]['hour_dew_point'] = $data['dew_point'];                      // Атмосферная температура (меняется в зависимости от давления и влажности), ниже которой капли воды начинают конденсироваться и может образовываться роса. Единицы в метрическая система: Цельсий
            $hour[$dt]['hour_clouds'] = $data['clouds'];                            // Облачность в %
            $hour[$dt]['hour_visibility'] = $data['visibility'];                    // видимость в метрах
            $hour[$dt]['hour_wind_speed'] = $data['wind_speed'];                    // скорость ветра
            $hour[$dt]['hour_wind_deg'] = $data['wind_deg'];                        // направление ветра, градусы (метеорологические)
            $hour[$dt]['hour_w_id'] = $data['weather'][0]['id'];                    // id текущей погоды
            $hour[$dt]['hour_w_main'] = $data['weather'][0]['main'];                // группа погодных условий
            $hour[$dt]['hour_description'] = $data['weather'][0]['description'];    // описание текущей погоды
            $hour[$dt]['hour_icon'] = $data['weather'][0]['icon'];                  // id иконки
            $hour[$dt]['hour_pop'] = $data['pop'];                                  // вероятность выпадения осадков

            $hour[$dt]['hour_wind_gust'] = $data['wind_gust'];                      // порыв ветра метр/сек (когда доступно)
            $hour[$dt]['hour_rain_1h'] = $data['rain'][0]['1h'];                    // Объем дождя за последний час, мм (когда доступно)
            $hour[$dt]['hour_snow_1h'] = $data['snow'][0]['1h'];                    // Объем снега за последний час, мм (когда доступно)
        }

        return $hour;
    }

    /* Ежедневный прогноз на ближайшие 8 дней */
    public function daily(){

        foreach ($this->data['daily'] as $data) {
            $dt = $data['dt'];
            $daily[$dt]['day_sunrise'] = $data['sunrise'];
            $daily[$dt]['day_sunset'] = $data['sunset'];
            $daily[$dt]['day_pressure'] = $data['pressure'];
            $daily[$dt]['day_humidity'] = $data['humidity'];
            $daily[$dt]['day_dew_point'] = $data['dew_point'];
            $daily[$dt]['day_wind_speed'] = $data['wind_speed'];
            $daily[$dt]['day_wind_deg'] = $data['wind_deg'];
            $daily[$dt]['day_clouds'] = $data['clouds'];
            $daily[$dt]['day_pop'] = $data['pop'];
            $daily[$dt]['day_uvi'] = $data['uvi'];
            $daily[$dt]['day_temp'] = $data['temp']['day'];
            $daily[$dt]['day_min'] = $data['temp']['min'];
            $daily[$dt]['day_max'] = $data['temp']['max'];
            $daily[$dt]['day_night'] = $data['temp']['night'];
            $daily[$dt]['day_eve'] = $data['temp']['eve'];
            $daily[$dt]['day_morn'] = $data['temp']['morn'];
            $daily[$dt]['day_feels_like_day'] = $data['feels_like']['day'];
            $daily[$dt]['day_feels_like_night'] = $data['feels_like']['night'];
            $daily[$dt]['day_feels_like_eve'] = $data['feels_like']['eve'];
            $daily[$dt]['day_feels_like_morn'] = $data['feels_like']['morn'];
            $daily[$dt]['day_w_id'] = $data['weather'][0]['id'];
            $daily[$dt]['day_w_main'] = $data['weather'][0]['main'];
            $daily[$dt]['day_w_description'] = $data['weather'][0]['description'];
            $daily[$dt]['day_w_icon'] = $data['weather'][0]['icon'];

            $daily[$dt]['day_wind_gust'] = $data['wind_gust'];                      // порыв ветра метр/сек (когда доступно)
            $daily[$dt]['day_rain_1h'] = $data['rain'][0]['1h'];                    // Объем дождя за последний час, мм (когда доступно)
            $daily[$dt]['day_snow_1h'] = $data['snow'][0]['1h'];
        }

        return $daily;
    }




}



/*
УФ индекс

НИЗКИЙ:
УФ-индекс 1-2 (low)
Защита не требуется
Можно спокойно находиться на улице

УМЕРЕННЫЙ/ВЫСОКИЙ:
УФ-индекс 3-5 (medium)
УФ-индекс 6-7 (high)
Требуется защита
Наденьте рубашку, головной убор и солнечные очки, используйте солнцезащитные средства

ОЧЕНЬ ВЫСОКИЙ/ ЭКСТРЕМАЛЬНЫЙ:
УФ-индекс 8-10 (very high)
УФ-индекс 11+ (extremely high)
Требуется дополнительная защита
Старайтесь не находиться на улице в полуденное время
Постарайтесь найти тень
В обязательном порядке носите рубашку, головной убор и солнечные очки и используйте солнцезащитные средства
*/