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

/**Функция проверки наличия доступа у пользователя к странице
 * @param $authAttribute - атрибут авторизации или иного признак, который требует проверки
 * @param $url - путь к странице для которой делается проверка
 * @param $redirectPath - страница, на которую делается редирект
 */
function isAuth($authAttribute, $url, $redirectPath)
{
    if (!$authAttribute && isCurrentURL($url)) {
        header("Location: $redirectPath"); //Редирект для пользователей не подходящих под признак
    } else {
        if (isset($_SESSION['login'])) {
            setcookie('login', $_COOKIE['login'], time() + 43200, '/');
        }
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

/**Функция, формирующая масиив категорий внутри массива с информацией о товаре
 * @param $arr - массив товаров
 * @return array - массив твоаров, содержащий масиив категорий, к которым этот товар относится
 */
function transformProductsArr($arr)
{
    $transformedArr = [];
    $i = 0;
    foreach ($arr as $elem) {

        if (empty($transformedArr)) {
            $transformedArr[$i] = $elem;
            $transformedArr[$i]['categoryTypes'] = [$elem['categoryType']];
            $transformedArr[$i]['categoryNames'] = [$elem['categoryName']];
        } elseif ($elem['id'] !== $transformedArr[$i]['id']) {
            $i++;
            $transformedArr[$i] = $elem;
            $transformedArr[$i]['categoryTypes'] = [$elem['categoryType']];
            $transformedArr[$i]['categoryNames'] = [$elem['categoryName']];
        } else {
            $transformedArr[$i]['categoryTypes'][] = $elem['categoryType'];
            $transformedArr[$i]['categoryNames'][] = $elem['categoryName'];
        }
    }

    return $transformedArr;
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
            if (isset($filterParamArr['category']) && in_array($filterParamArr['category'], $product['categoryTypes'], true)) {
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

/** Функция фильтрации по определенному полю
 * @param array $array - фильтруемый массив
 * @param string $field - ключь массива по которому нужно его отфильтровать
 * @param string $filter - значение по которому происзодит фильтрация
 * @param bool $isArray - является ли поле ключем массива
 * @return array - отфильтрованный массив
 */
function queryFilter(array $array, string $field, string $filter, bool $isArray = false)
{
    $filteredArr = [];

    foreach ($array as $arrElem) {
        if ($arrElem[$field] === $filter || $isArray && in_array($filter, $arrElem[$field])) {
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

    $productPages = []; //массив страниц с товарами
    $currentItem = 0; //индекс товара в массиве товаров
    for ($i = 1; $i <= $pages; $i++) {
        $productPage = []; //массив из товаров входящих в одну страницу
        for (; $currentItem <= $productsCount - 1; $currentItem++) {
            if ($currentItem <= $limit - 1) {
                $productPage[] = $productsArr[$currentItem]; //записываем в массив страницы товары, которые будут на ней отображаться
            } else {
                //Если число товаров превышает предел на странице то увеличиваем его для отображения последующих
                // и разрываем цикл для перехода к следующей странице
                $limit += 9;
                break;
            }
        }
        $productPages[$i] = $productPage; //добавляем страницу в массив страниц
    }
    //вывод контента определнной страницы
    if (array_key_exists($currentPage, $productPages)) {
        foreach ($productPages[$currentPage] as $product) {
            include $_SERVER['DOCUMENT_ROOT'] . '/templates/productItem.php'; //вывод списка товаров
        }
    } else {
        include $_SERVER['DOCUMENT_ROOT'] . '/templates/noProductsMsg.php'; //сообщение об отсутствие подходящего под фильтр товара
    }

}

/**Функция отображения доступных страниц с товарами
 * @param int $pages - количество страниц с товарами
 * @param int $currentPage - текущая страница
 */
function showPaginator(int $pages, int $currentPage)
{
    $changedPageParams = '';
    $productsParams = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
    $prevPage = preg_match('/page=(.*?)&/', $productsParams, $match);
    if (isset($match[1])) {
        $changedPageParams = str_replace("page=$match[1]&", '', $productsParams);
    } else {
        $changedPageParams = str_replace("page=$prevPage&", '', $productsParams);
    }
    include $_SERVER['DOCUMENT_ROOT'] . '/templates/paginator.php';
}

/**Функция отображения списка заказов
 * @param $ordersArr - массив с информацией о заказах
 */
function showOrders($ordersArr)
{
    foreach ($ordersArr as $order) {
        include $_SERVER['DOCUMENT_ROOT'] . '/templates/orderItem.php';
    }
}

/**Функция отображения списка заказов
 * @param $productsArr - массив с информацией о продуктах
 */
function showAdminProducts($productsArr)
{
    foreach ($productsArr as $product) {
        include $_SERVER['DOCUMENT_ROOT'] . '/templates/adminProduct.php';
    }
}

/**Функция получения src атрибута в формате base64
 * @param $img - путь до файла изображения
 * @return string - строка для src тега img
 */
function getBase64CodeImg($img)
{
    $imgSize = getimagesize($img);
    $imgData = base64_encode(file_get_contents($img));
    $imgSrc = "data:{$imgSize['mime']};base64,{$imgData}";
    return $imgSrc;
}
