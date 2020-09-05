<?php

class viewController
{
    /**
     * Подключение параметров к странице
     */
    public static function display($data){

        // Получаем шаблон
        $page = get_template('layers', 'template_page', 'full');

        // Подключаем стили
        $page = setm($page, 'style', '<link rel="stylesheet" href="/sources/css/style.css">');

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


}