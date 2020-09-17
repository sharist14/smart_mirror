<?php
require_once(WWW.'/views/viewTemplate.php');

class viewController extends viewTemplate
{
    /**
     * Подключение параметров к странице
     */
    public static function display($data, $params=''){

        // Получаем шаблон
        $page = get_template('layers', 'main_page', 'full');

        // Подключаем общие стили стили
        $page = setm($page, 'style', '<link rel="stylesheet" href="/sources/css/style.css">');
        $page = setm($page, 'style', '<link rel="stylesheet" href="/sources/css/bootstrap.min.css">');

        // Подключаем стили от модулей
        $params? $page = self::connect_params($page, 'style', $params):NULL;

        // Подключаем общие скрипты
        $page = setm($page, 'script', '<script src="/sources/js/bootstrap.min.js"></script>');
        $page = setm($page, 'script', '<script src="/sources/js/bootstrap.bundle.min.js"></script>');

        // Добавляем контент в body
        $page = set($page, 'content', $data);

        self::render($page);
    }

    /**
     * Отображение страницы клиенту
     */
    public static function render($page){

        // Убираем слова в фигурных скобках если такие есть
        if(preg_match("~{(.*)}~", $page)) {
            $replace = preg_replace("~{(.*)}~", '', $page);

            // Выводим страницу пользователю
            print($replace);
        }
    }

    // Подключение параметров к странице
    public static function connect_params($page, $type, $params){

        // Ищем в параметре тип который необходимо добавить
        foreach($params as $param => $str_arr){
            if($param == $type){
                foreach($str_arr as $str){
                    $page = setm($page, $type, $str);
                }
            }
        }

        
        return $page;
    }


}