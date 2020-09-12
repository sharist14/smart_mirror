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

        return ($result[1])? : die('Проблема с файлом или шаблоном');
    }
}

/**
 * Вставка данных в шаблон
 */
function set($template, $area, $value){

    // Ищем область по шаблону
    if(preg_match("~{".$area."}~", $template)){
        $replace = preg_replace("~{".$area."}~", $value, $template);

        return $replace;

    } else{
        return $template;
    }
}

/**
 * Множественная вставка данных в шаблон
 */
function setm($template, $area, $value){

    // Ищем область по шаблону
    if(preg_match("~{".$area."}~", $template)){
        $replace = preg_replace("~{".$area."}~", $value.'{'.$area.'}', $template);

        return $replace;

    } else{
        return $template;
    }
}

/**
 * Вывод информации в удобном виде
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
function tf($temp){

    $temp = round($temp).'&#8451;';

    return $temp;
}

/**
 * Отображение даты в читаемом виде
 */
function df($date, $format = 's'){    //"s" - short(12.05), "m" - middle(12.05.2020), "f" - full(12.05.2020 15:26)

    switch($format){
        case "s":
            $format = "d.m";
            break;
        case "m":
            $format = "d.m.Y";
            break;
        case "f":
            $format = "d.m.Y H:i:s";
            break;
    }

    return date($format, $date);
}

/**
 * Направление ветра
 */
function wind_deg($deg){

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