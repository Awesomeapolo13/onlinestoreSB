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

/**Функция отображения заголовка в теге title
 * @param array $titleArray - массив заголовков
 * @return mixed|string - заголовок для страницы в соответствии с переданным массивом
 */
function showHeadTitle(array $titleArray)
{
    foreach ($titleArray as $title) {
        if (isCurrentURL($title['path'])) {
            return $title['title'];
        }
    }
    return 'Error 404';
}

/**Функция отображения контента на странице
 * @param array $contentArray - массив информации о пути и заголовке страницы
 * @param false $admin - атрибут указывающий на принадлежность контента к административной части
 * @return mixed - файл содержащий контент страницы
 */
function showContent(array $contentArray, $admin = false)
{
    if ($admin) {
        foreach ($contentArray as $contentItem):
            if (isCurrentURL($contentItem['path']) && $contentItem['title'] !== 'Главная'):
                return include $_SERVER['DOCUMENT_ROOT'] . $contentItem['path'] . '.php';
            endif;
        endforeach;
    } else {
        foreach ($contentArray as $contentItem):
            if (isCurrentURL($contentItem['path']) && $contentItem['title'] !== 'Главная'):
                return include $_SERVER['DOCUMENT_ROOT'] . '/route' . $contentItem['path'] . '.php';
            endif;
        endforeach;
    }
    return include $_SERVER['DOCUMENT_ROOT'] . '/route/index.php';

}

function showMenu (array $menuArray, string $menuClass = 'footer', bool $admin = false)
{
    include $_SERVER['DOCUMENT_ROOT'] . '/templates/menu.php';
}