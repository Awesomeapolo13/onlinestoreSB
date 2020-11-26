<?php

namespace pageHelper;

/** Функция определения активного пункта меню
 * @param $url - путь к странице определнный массивом главного меню
 * @return bool - true в случае совпадения текущего url с url хранящемся в массиве меню, false в случае несовпадения
 */
function isCurrentURL($url)
{
    return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) == $url;
}

function showHeadTitle(array $titleArray)
{
    foreach ($titleArray as $title) {
        if (isCurrentURL($title['path'])) {
            return $title['title'];
        }
    }
    return 'Error 404';
}