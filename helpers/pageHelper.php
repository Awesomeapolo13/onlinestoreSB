<?php

namespace pageHelper;

/** Функция определения активного пункта меню
 * @param $url - путь к странице определнный массивом главного меню
 * @param bool $query - флаг учета query парамметров
 * @return bool - true в случае совпадения текущего url с url хранящемся в массиве меню, false в случае несовпадения
 */
function isCurrentURL($url, $query = false)
{
    $urlQuery = parse_url($url, PHP_URL_QUERY); //get-парамметры передаваемого в функцию пути
    $urlPath = parse_url($url, PHP_URL_PATH); // сам путь
    if ($query) {
        return (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) == $urlPath && parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) == $urlQuery);
    } else {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) == $url;
    }
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
                include $_SERVER['DOCUMENT_ROOT'] . $contentItem['path'] . '.php';
                return;
            endif;
        endforeach;
    } else {
        foreach ($contentArray as $contentItem):
            if (isCurrentURL($contentItem['path']) && $contentItem['title'] !== 'Главная'):
                include $_SERVER['DOCUMENT_ROOT'] . '/route' . $contentItem['path'] . '.php';
                return;
            endif;
        endforeach;
    }
    include $_SERVER['DOCUMENT_ROOT'] . '/route/index.php';
}

/**Функция отображения меню
 * @param array $menuArray - массив параммтров для построения меню
 * @param null $auth - признак авторизации (по умолчанию null)
 * @param string $menuClass - тип меню для отображения ('header' или 'footer')
 */
function showMenu(array $menuArray, $auth = null, string $menuClass = 'footer')
{
    $admin = false;
    if (isset($auth)) {
        $admin = true;
    }
    include $_SERVER['DOCUMENT_ROOT'] . '/templates/menu.php';
}

/**Функция фильтрации массива товаров
 * @param array $productsArr - массив товаров, полученный из БД
 * @param array $filterParamArr - массив параметров для филтрации
 * @return array - отфильтрованный массив
 */
function filterProducts(array $productsArr, array $filterParamArr)
{
    $filteredProducts = [];
    foreach ($productsArr as $product) {
        if ($product['price'] >= $filterParamArr['minPrice'] && $product['price'] <= $filterParamArr['maxPrice']) {
            if (isset($filterParamArr['category']) && $filterParamArr['category'] === $product['categoryType']) {
                $filteredProducts[] = $product;
            }
            if (isset($filterParamArr['new']) && $filterParamArr['new'] === 'on' && $product['new'] && !in_array($product, $filteredProducts)) {
                $filteredProducts[] = $product;
            }
            if (isset($filterParamArr['sale']) && $filterParamArr['sale'] === 'on' && $product['sale'] && !in_array($product, $filteredProducts)) {

                $filteredProducts[] = $product;
            }
            if (!in_array($product, $filteredProducts)) {
                $filteredProducts[] = $product;
            }
        }
    }
    return $filteredProducts;
}

function queryFilter($array, $field, $filter)
{
    $filteredArr = [];
    foreach ($array as $arrElem)
        if ($arrElem[$field] === $filter) {
            $filteredArr[] = $arrElem;
        }
    return $filteredArr;
}

/**Функция отображения товаров
 * @param $productsArr - массив товаров
 */
function showProducts($productsArr)
{
    foreach ($productsArr as $product):
        include $_SERVER['DOCUMENT_ROOT'] . '/templates/productItem.php'; //вывод списка товаров
    endforeach;
}