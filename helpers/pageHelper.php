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

/** Функция фитрации по определенному полю
 * @param array $array - фильтруемый массив
 * @param string $field - ключь массива по которому нужно его отфильтровать
 * @param string $filter - значение по которому происзодит фильтрация
 * @return array - отфильтрованный массив
 */
function queryFilter(array $array, string $field, string $filter)
{
    $filteredArr = [];
    foreach ($array as $arrElem) {
        if ($arrElem[$field] === $filter) {
            $filteredArr[] = $arrElem;
        }
        if (!$filter) { //если фильтр не задан включаем все элементы массива
            $filteredArr[] = $arrElem;
        }
    }
    return $filteredArr;
}

/**Функция сортировки массива по ключу
 * @param array $array массив, который необходимо отсортировать
 * @param $key - значение ключа подмассива, по которому необходимо произвести сортировку
 * @param int $sort - порядок сортировки, SORT_ASC - прямой, SORT_DESC - обратный порядок
 * @return array - массив отсортированный в соответствии с переданными парамметрами
 */
function sortProducts(array $array, $key, $sort = SORT_ASC)
{
    usort($array, function ($item1, $item2) use ($key, $sort) {
        switch ($sort) {
            case SORT_ASC:
                return $item1[$key] <=> $item2[$key];
            case SORT_DESC:
                return $item2[$key] <=> $item1[$key];
        }
    });

    return $array;
}

/**Функция отображения товаров
 * @param array $productsArr - массив товаров
 * @param int $pages - колличество страниц с товарами
 * @param int $currentPage - текущая страница
 */
function showProducts(array $productsArr, int $pages, $currentPage = 1)
{
    $limit = null; //максимальное колличество позиция товара на одной странице
    $productsCount = count($productsArr);
    if ($productsCount >= 9) {
        $limit = 9;
    } else {
        $limit = count($productsArr);
    }

    $productPage = []; //массив из товаров входящих в одну страницу
    $productPages = []; //массив страниц с товарами
    for ($i = 1; $i <= $pages; $i++) {
        for ($j = 0; $j <= $limit - 1; $j++) {
            $productPage[] = $productsArr[$j]; //записываем в массив страницы товары, которые будут на ней отображаться
        }
        $productPages[$i] = $productPage; //добавляем страницу в массив страниц
    }
    //вывод контента определнной страницы
    foreach ($productPages[$currentPage] as $product) {
        include $_SERVER['DOCUMENT_ROOT'] . '/templates/productItem.php'; //вывод списка товаров
    }
}

/**Функция отображения доступных страниц с товарами
 * @param int $pages - количество страниц с товарами
 * @param int $currentPage - текущая страница
 */
function showPaginator(int $pages, int $currentPage)
{
    $productsParams = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
    for ($page = 1; $page <= $pages; $page++) {
        include $_SERVER['DOCUMENT_ROOT'] . '/templates/paginator.php';
    }
}