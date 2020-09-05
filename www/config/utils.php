<?php

/**
 * Получить шаблон из файла
 */
function get_template($folder, $file, $area){

    // Проверяем наличие файла с шаблоном
    if( file_exists($way =WWW.'/views/'.$folder.'/'.$file.'.html') ){

        // Шаблон по ключевым словам
        $template = "~\#\[".$area."\]\#(.+?)\#\[\!".$area."\]\#~is";

        // Ищем область по шаблону
        preg_match($template, file_get_contents($way), $result);

        return ($result[1])? : die('Проблема с файлом или шаблоном');
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
        case "Array":
            $type_pr = 'pre_';
            $print = $data;
            break;
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
 * Setter для вставки данных
 */
function set($template, $value){
    pre($template);
    pre($value);

}
