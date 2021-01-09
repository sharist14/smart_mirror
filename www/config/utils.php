<?php

/**
 * Получить шаблон из файла
 */
function get_template($folder, $file, $area){

    // Проверяем наличие файла с шаблоном
    if( file_exists($way =WWW.'/template/'.$folder.'/'.$file.'.html') ){

        // Шаблон по ключевым словам
        $template = "~\#\[".$area."\]\#(.+?)\#\[\!".$area."\]\#~is";

        // Ищем область по шаблону
        preg_match($template, file_get_contents($way), $result);

        return ($result[1])? : die('Проблема с вызовом шаблона <span style="color:red;font-weight: bold">{'.$area.'}</span> в файле <span style="color:red;font-weight: bold">/www/template/'.$folder.'/'.$file.'.htm</span>');
    }
}



/**
 * Вставка данных в шаблон
 */
function set($template, $area, $value){

    // Если есть массив с данными, первый элемент оригинальное значение, а второй - отформатированное
    if(is_array($value)){
        $value_orig = $value[1];
        $value = $value[0];
    }


    // Вставляем данные
    if(strrpos($template, "{".$area."}")){
        $template = preg_replace("~{".$area."}~", $value, $template);
    }

    // Вставляем оригинальные данные
    if(strrpos($template, "{".$area."_orig}")){
        $template = preg_replace("~{".$area."_orig}~", $value_orig, $template);
    }

    return $template;
}



/**
 * Множественная вставка данных в шаблон
 */
function setm($template, $area, $value){

    // Если есть массив с данными, первый элемент оригинальное значение, а второй - отформатированное
    if(is_array($value)){
        $value_orig = $value[1];
        $value = $value[0];
    }

    // Вставляем данные
    if(strrpos($template, "{".$area."}")){
        $template = preg_replace("~{".$area."}~", $value.'{'.$area.'}', $template);
    }

    // Вставляем оригинальные данные
    if(strrpos($template, "{".$area."_orig}")){
        $template = preg_replace("~{".$area."_orig}~", $value_orig.'{'.$area.'_orig}', $template);
    }

    return $template;
}



/**
 * Вывод технической информации в удобном виде
 */
function pre($data){

    $type = ucfirst(gettype($data));

    switch($type){
        case "String":
            $type_pr = 'pre_'.$type.' ';
            $print = '"'.$data.'"';
            break;
        case "Object":
        case "Array":
            $type_pr = 'pre_';
            $print = $data;
            break;
        case "Double":
        case "Integer":
            $type_pr = 'pre_'.$type.' ';
            $print = $data;
            break;
    }

    echo "<pre style='color: #ffffff;'><span style='color: #ff5100; font-weight: bold; font-size: 16px;'>" .$type_pr.'</span>';
    print_r($print);
    echo "</pre>";

    return true;
}



/**
 * Отображение в формате температуры
 */
function tf($temp, $format = 'celsius'){

    switch ($format){
        case 'min':
            $display = '&#176;'; // degree
            break;
        case 'celsius':
            $display = '&#8451;'; // degree Celsius
            break;
    }

    $temp = round($temp).$display;

    return $temp;
}



/**
 * Отображение даты в читаемом виде
 */
function df($date, $format = 'fd'){

    switch($format){
        case "sd":                       //"sd" - short date (12.05)
            $format = "d.m";
            break;
        case "fd":                       //"fd" - full date (12.05.2020)
            $format = "d.m.Y";
            break;
        case "dt":                       //"dt" - full(12.05.2020 15:26)
            $format = "d.m.Y H:i:s";
            break;
        case "st":                       // "st" - short time(18:15)
            $format = "H:i";
            break;
        case "ft":                       // "ft" - full time(18:15:51)
            $format = "H:i:s";
            break;
    }

    return date($format, $date);
}



/**
 * Направление ветра
 */
function wind_arrow($deg){

    switch($deg) {
        case ($deg <= 22):
            $direct = 'cевер';
            break;
        case ($deg <= 67):
            $direct = 'cеверо-восток';
            break;
        case ($deg <= 112):
            $direct = 'восток';
            break;
        case ($deg <= 157):
            $direct = 'юго-восток';
            break;
        case ($deg <= 202):
            $direct = 'юг';
            break;
        case ($deg <= 247):
            $direct = 'юго-запад';
            break;
        case ($deg <= 292):
            $direct = 'запад';
            break;
        case ($deg <= 337):
            $direct = 'северо-запад';
            break;
        case ($deg <= 360):
            $direct = 'север';
            break;
    }

    return $direct;
}



// Первая заглавная буква (для utf-8)
function ucfirst_utf8($str){
    return mb_substr(mb_strtoupper($str, 'utf-8'), 0, 1, 'utf-8') . mb_substr($str, 1, mb_strlen($str)-1, 'utf-8');
}



// Определяем день недели
function day_of_week($num_day, $format){
    $title = [
        1 => [
            'ru_full' => 'Понедельник',
            'ru_short' => 'Пн'
        ],
        2 => [
            'ru_full' => 'Вторник',
            'ru_short' => 'Вт'
        ],
        3 => [
            'ru_full' => 'Среда',
            'ru_short' => 'Ср'
        ],
        4 => [
            'ru_full' => 'Четверг',
            'ru_short' => 'Чт'
        ],
        5 => [
            'ru_full' => 'Пятница',
            'ru_short' => 'Пт'
        ],
        6 => [
            'ru_full' => 'Суббота',
            'ru_short' => 'Сб'
        ],
        0 => [
            'ru_full' => 'Воскресенье',
            'ru_short' => 'Вс'
        ],

    ];

    return $title[$num_day][$format];
}


// Определяем иконку погоды
// https://openweathermap.org/weather-conditions#How-to-get-icon-URL
function getNameWeatherIcon($wather_arr){

    $icon_arr = [
        // group 2хх: Thunderstorm (Гроза)
        '200' => ['default' => '2xx_3',     'sunny' => '2xx_2'],   // is_sunny
        '201' => ['default' => '2xx_3',     'sunny' => ''],
        '202' => ['default' => '2xx_4',     'sunny' => ''],
        '210' => ['default' => '2xx_1',     'sunny' => '2xx_2'],   // is_sunny
        '211' => ['default' => '2xx_1',     'sunny' => ''],
        '212' => ['default' => '2xx_1',     'sunny' => ''],
        '221' => ['default' => '2xx_1',     'sunny' => ''],
        '230' => ['default' => '2xx_3',     'sunny' => '2xx_2'],   // is_sunny
        '231' => ['default' => '2xx_3',     'sunny' => ''],
        '232' => ['default' => '2xx_4',     'sunny' => ''],

        // group 3хх: Drizzle (Морось)
        '300' => ['default' => '4xx_1',     'sunny' => '3xx'],   // is_sunny
        '301' => ['default' => '4xx_1',     'sunny' => '3xx'],   // is_sunny
        '302' => ['default' => '4xx_1',     'sunny' => '3xx'],   // is_sunny
        '310' => ['default' => '4xx_1',     'sunny' => '3xx'],   // is_sunny
        '311' => ['default' => '4xx_1',     'sunny' => '3xx'],   // is_sunny
        '312' => ['default' => '4xx_1',     'sunny' => '3xx'],   // is_sunny
        '313' => ['default' => '4xx_1',     'sunny' => '3xx'],   // is_sunny
        '314' => ['default' => '4xx_1',     'sunny' => '3xx'],   // is_sunny
        '321' => ['default' => '4xx_1',     'sunny' => '3xx'],   // is_sunny

        // group 5хх: Rain (Дождь)
        '500' => ['default' => '4xx_1',     'sunny' => ''],
        '501' => ['default' => '4xx_2',     'sunny' => ''],
        '502' => ['default' => '4xx_3',     'sunny' => ''],
        '503' => ['default' => '4xx_3',     'sunny' => ''],
        '504' => ['default' => '4xx_3',     'sunny' => ''],
        '511' => ['default' => '8xx_4',     'sunny' => ''],
        '520' => ['default' => '4xx_1',     'sunny' => '3xx'],   // is_sunny
        '521' => ['default' => '4xx_2',     'sunny' => '3xx'],   // is_sunny
        '522' => ['default' => '4xx_3',     'sunny' => '3xx'],   // is_sunny
        '531' => ['default' => '4xx_4',     'sunny' => ''],

        // group 6хх: Snow (Снег)
        '600' => ['default' => '6xx_1',     'sunny' => ''],
        '601' => ['default' => '6xx_2',     'sunny' => ''],
        '602' => ['default' => '6xx_3',     'sunny' => ''],
        '611' => ['default' => '6xx_4',     'sunny' => ''],
        '612' => ['default' => '6xx_4',     'sunny' => '6xx_5'],   // is_sunny
        '613' => ['default' => '6xx_6',     'sunny' => ''],
        '615' => ['default' => '6xx_6',     'sunny' => ''],
        '616' => ['default' => '6xx_4',     'sunny' => ''],
        '620' => ['default' => '6xx_1',     'sunny' => '6xx_9'],   // is_sunny
        '621' => ['default' => '6xx_2',     'sunny' => '6xx_10'],   // is_sunny
        '622' => ['default' => '6xx_3',     'sunny' => '6xx_10'],   // is_sunny

        // group 7хх: Atmosphere (Атмосфера)
        '701' => ['default' => '8xx_4',     'sunny' => ''],
        '711' => ['default' => '8xx_4',     'sunny' => ''],
        '721' => ['default' => '8xx_4',     'sunny' => ''],
        '731' => ['default' => '8xx_4',     'sunny' => ''],
        '741' => ['default' => '8xx_4',     'sunny' => ''],
        '751' => ['default' => '8xx_4',     'sunny' => ''],
        '761' => ['default' => '8xx_4',     'sunny' => ''],
        '762' => ['default' => '8xx_4',     'sunny' => ''],
        '771' => ['default' => '8xx_4',     'sunny' => ''],
        '781' => ['default' => '8xx_4',     'sunny' => ''],

        // group 800: Clear (Ясно)
        '800' => ['default' => '888',       'sunny' => ''],   // is_sunny

        // group 80х: Clouds (Облачность)
        '801' => ['default' => '8xx_4',     'sunny' => '8xx_1'],   // is_sunny
        '802' => ['default' => '8xx_4',     'sunny' => '8xx_2'],   // is_sunny
        '803' => ['default' => '8xx_4',     'sunny' => '8xx_3'],   // is_sunny
        '804' => ['default' => '8xx_4',     'sunny' => ''],
    ];


    // Проверяем не ночь ли сейчас (для отображения ночной иконки)
    $night = isNightTime();

    // Проверяем есть ли солнечный свет
    $sunlight = isSunLight($wather_arr['curr_sunrise'][0], $wather_arr['curr_sunset'][0]);

    // Наличие большого количества облаков
    $cloudly = ($wather_arr['curr_clouds'] > 25)? true : false;


    // Determine icon
    if(!$night){

        // Иконка по умолчанию
        $cur_id = $wather_arr['curr_w_id'];
        $icon_name = $icon_arr[$cur_id]['default'];

        // Если есть солнечный свет, малооблачно и есть солнечная иконка - то используем её
        // if is sunlight and clouds < 25% and is sunny icon
        if($sunlight && !$cloudly && $icon_arr[$cur_id]['sunny']){
            $icon_name = $icon_arr[$cur_id]['sunny'];
        }

    } else{
        $icon_name = '000';     // Иконка для ночного времени суток
    }

    return $icon_name;
}



// Проверка на время суток - ночь
function isNightTime(){
    $curr_time = date('H');

    return ($curr_time >= 22 || $curr_time <= 5 )? true : false;
}



// Проверка на наличие солнечного света
function isSunLight($sunrise, $sunset){
    $time_now = date('H:i:s');

    return ( ($time_now > $sunrise) && ($time_now < $sunset))? true : false;
}


//Качественная характеристика скорости ветра
//Диапазон скорости ветра, м/с
//
//Слабый      0-5
//Умеренный   6-14
//Сильный     15-24
//Очень сильный 25-32
//Ураганный 33 и более
function powerWind($speed){
    switch($speed){
        case ($speed < 6):
            $power = 'cлабый';
            break;
        case ($speed < 15):
            $power = 'умеренный';
            break;
        case ($speed < 25):
            $power = 'сильный';
            break;
        case ($speed < 33):
            $power = 'очень сильный';
            break;
        case ($speed):
            $power = 'ураганный';
            break;
    }

    return $power;
}



//Кол-во осадков дождя, мм/12 час
//
//Без осадков, сухая погода - 0 мм.
//Небольшой дождь, слабый дождь, морось, моросящие осадки, небольшие осадки - 0-2 мм.
//Дождь, дождливая погода, осадки, мокрый снег, дождь со снегом; снег, переходящий в дождь; дождь, переходящий в снег 3-14 мм.
//Сильный дождь, ливневый дождь (ливень), сильные осадки, сильный мокрый снег, сильный дождь со снегом, сильный снег с дождем 15-49 мм.
//Очень сильный дождь, очень сильные осадки (очень сильный мокрый снег, очень сильный дождь со снегом, очень сильный снег с дождем) ≥ 50  мм.
//
//
//
//Кол-во осадков снега, мм/12 час
//
//Без осадков, сухая погода -  0 мм.
//Небольшой снег, слабый снег 0-1 мм.
//Снег, снегопад 2-5 мм.
//Сильный снег, сильный снегопад 6-19 мм.
//Очень сильный снег, очень сильный снегопад ≥ 20
function powerRainSnow($weather_id, $precipitation){

    // по умолчанию количество осадков приходит в размере мм/мин
    // переводим в мм/12 ч
    $precipitation = $precipitation * 60 * 12;

    //$icon_n = '<i class="fas fa-ban"></i>';                // old none icon
    $icon_n = '<span class="none-dash">---</span>';          // none
    $icon_r = '<i class="rain_ico fas fa-tint"></i>';        // rain
    $icon_s = '<i class="snow_ico fas fa-snowflake"></i>';   // snow

    // determine type precipitation
    switch($weather_id){

        // SNOW
        case '600':   // light snow (небольшой снегопад)
        case '601':   // Snow (снегопад)
        case '602':   // Heavy snow (сильный снегопад)
        case '620':   // Light shower snow (кратковременный небольшой снегопад)
        case '621':   // Shower snow (кратковременный снегопад)
        case '622':   // Heavy shower snow (кратковременный сильный снегопад)

            if($precipitation == 0){              // Без осадков
                $icon_block = $icon_n;

            } elseif( $precipitation <= 1 ){      // Небольшой снег, слабый снег
                $icon_block = $icon_s;

            } elseif( $precipitation <= 5 ){      // Снег, снегопад
                $icon_block = $icon_s.$icon_s;

            } elseif( $precipitation <= 19 ){     // Сильный снег, сильный снегопад
                $icon_block = $icon_s.$icon_s.$icon_s;

            } elseif( $precipitation >= 50 ){     // Очень сильный снег, очень сильный снегопад
                $icon_block = $icon_s.$icon_s.$icon_s.$icon_s;

            }
            break;

        // RAIN
        default :   // Snow (снег)
            if($precipitation == 0){              // Без осадков
                $icon_block = $icon_n;

            } elseif( $precipitation <= 2 ){      // Небольшой дождь, слабый дождь, морось, моросящие осадки, небольшие осадки
                $icon_block = $icon_r;

            } elseif( $precipitation <= 14 ){     // Дождь, дождливая погода, осадки, мокрый снег, дождь со снегом; снег, переходящий в дождь; дождь, переходящий в снег
                $icon_block = $icon_r.$icon_r;

            } elseif( $precipitation <= 49 ){     // Сильный дождь, ливневый дождь (ливень), сильные осадки, сильный мокрый снег, сильный дождь со снегом, сильный снег с дождем
                $icon_block = $icon_r.$icon_r.$icon_r;

            } elseif( $precipitation >= 50 ){     // Очень сильный дождь, очень сильные осадки (очень сильный мокрый снег, очень сильный дождь со снегом, очень сильный снег с дождем)
                $icon_block = $icon_r.$icon_r.$icon_r.$icon_r;
            }

            break;
    }


    return $icon_block;
}
