<?php
require_once(WWW.'/views/viewTemplate.php');

class viewController extends viewTemplate
{
    /**
     * Подключение параметров к странице
     */
    public static function display($data, $params=''){

        if(preg_match("~{(.*)}~", $data)) {
            $data = preg_replace("~{(.*)}~", '', $data);
        }
        

        // Получаем шаблон
        $page = get_template('layers', 'index', 'full');

        // Подключаем общие стили стили
        $page = setm($page, 'head', '<link rel="stylesheet" href="/sources/css/style.css">');
        $page = setm($page, 'head', '<link rel="stylesheet" href="/sources/css/bootstrap.min.css">');
        $page = setm($page, 'head', '<link rel="stylesheet" href="/sources/css/fontawesome_all.css">');
        $page = setm($page, 'head', '<link rel="stylesheet" href="/sources/css/media_query.css">');

        // Подключаем стили от модулей
        $params? $page = self::connect_params($page, 'head', $params):NULL;

        $page = setm($page, 'head', '<script src="/sources/js/jquery-3.5.1.min.js"></script>');
        $page = setm($page, 'head', '<script src="/sources/js/bootstrap.min.js"></script>');
        $page = setm($page, 'head', '<script src="/sources/js/bootstrap.bundle.min.js"></script>');

        // Добавляем контент в body
        $page = set($page, 'content', $data);

        self::render($page);
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

    /**
     * Отображение страницы клиенту
     */
    public static function render($page){

        // Убираем слова в фигурных скобках если такие есть
        $tag_del = ['{head}', '{script}', '{content}'];
        $page = str_replace($tag_del,'',$page);

        print($page);
    }
}