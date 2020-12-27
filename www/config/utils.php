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

    echo "<pre style='color: #060406;'><span style='color: #ff5100; font-weight: bold; font-size: 16px;'>" .$type_pr.'</span>';
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



// Определяем время суток
function time_day(){

    $cur_date = date('H:i:s');
    if( ($cur_date > $this->wObj->sunrise) && ($cur_date < $this->wObj->sunset)){
        $str = 'Сейчас светло';
    } else{
        $str = 'Сейчас темно';
    }

    return $str;
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