<?php

namespace requestDBHelper;

/** Функция по установке соединения с базой данных
 * @return false|\mysqli|null - врзвращает соединение с базой данных
 */
function getConnection()
{
    include $_SERVER['DOCUMENT_ROOT'] . '/config/index.php';
    static $connection = null;
    if ($connection === null) {
        $connection = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName) or die('connection Error');
    }
    return $connection;
}

/** Функция - запрос данных для авторизации пользователя
 * @param $login - адрес электронной почты пользователя (логин), для которого делается запрос
 * @return array - массив, содержащий пароль и электронную почту пользователя
 */
function getUserByLogin($login)
{
    $loginMySQL = mysqli_real_escape_string(getConnection(), $login);
    if ($requestAuth = mysqli_query(getConnection(),
        "select id, email, password from users where email='$loginMySQL'")) {
        return mysqli_fetch_all($requestAuth, MYSQLI_ASSOC);
    }
}

/**
 * @param $id
 * @return array
 */
function getUserGroup($id)
{
    $idMySQL = mysqli_real_escape_string(getConnection(), $id);
    if ($requestGroups = mysqli_query(getConnection(),
        "select `groups`.name as 'groupName' from users
                left join group_user on users.id = group_user.user_id
                left join `groups` on group_user.group_id = `groups`.id where users.id = '$idMySQL'")) {
        return mysqli_fetch_all($requestGroups, MYSQLI_ASSOC);
    }
}

/** Функция - запрос информации о товарах и их категориях
 * @param bool $admin - атрибут включающий сортировку для админского раздела товаров
 * @return array - массив с информацией о товарах и их категориях
 */
function getProducts($admin = false)
{
    $query = "select products.id, products.name, products.price, products.img_name as 'imgName', products.new, products.sale, c.name as 'categoryName', c.type as 'categoryType'
from products
         left join category_product cp on products.id = cp.product_id
         left join categories c on cp.category_id = c.id order by products.id";
    //Порядок сортировки для админского раздела товаров
    $admin ? $query .= " DESC" : null;
    if ($requestProducts = mysqli_query(getConnection(), $query)) {
        return mysqli_fetch_all($requestProducts, MYSQLI_ASSOC);
    }
}

/**Функция - запрос имеющихся категорий
 * @return array - массив категорий товаров
 */
function getCategories()
{
    if ($requestCategiries = mysqli_query(getConnection(),
        "select * from categories")) {
        return mysqli_fetch_all($requestCategiries, MYSQLI_ASSOC);
    }
}

/**Функция - запрос на добавление заказа
 * @param $orderArr - массив с информацией о навом заказе
 * @param $orderPrice - цена заказа с учетом стоимости доставки
 * @return bool|\mysqli_result - информация об успешности запроса
 */
function createOrder($orderArr, $orderPrice)
{
    $name = mysqli_real_escape_string(getConnection(), $orderArr['name']); // имя заказчика
    $surname = mysqli_real_escape_string(getConnection(), $orderArr['surname']); // фамилия заказчика
    $timeStamp = date("Y-m-d H:i:s"); // время отправления
    $delivery = mysqli_real_escape_string(getConnection(), $orderArr['delivery']); // тип доставки
    $pay = mysqli_real_escape_string(getConnection(), $orderArr['pay']); // способ оплаты
    $comment = mysqli_real_escape_string(getConnection(), $orderArr['comment']); // тклкфон заказчика
    $productId = mysqli_real_escape_string(getConnection(), $orderArr['productId']); // идентификатор товара
    $price = mysqli_real_escape_string(getConnection(), $orderPrice); // цена заказа с учетом стоимости доставки
    $email = mysqli_real_escape_string(getConnection(), $orderArr['email']); // электронная почта заказчика
    $phone = mysqli_real_escape_string(getConnection(), $orderArr['phone']); // тклкфон заказчика
    $patronymic = 'null'; // отчество заказчика
    if (!empty($orderArr['patronymic'])) {
        $patronymic = mysqli_real_escape_string(getConnection(), $orderArr['patronymic']);
        $patronymic = "'$patronymic'";
    }
    $city = 'null'; // город
    if (!empty($orderArr['city'])) {
        $city = mysqli_real_escape_string(getConnection(), $orderArr['city']);
        $city = "'$city'";
    }
    $street = 'null'; // улица
    if (!empty($orderArr['street'])) {
        $street = mysqli_real_escape_string(getConnection(), $orderArr['street']);
        $street = "'$street'";
    }
    $home = 'null'; // дом
    if (!empty($orderArr['home'])) {
        $home = mysqli_real_escape_string(getConnection(), $orderArr['home']);
        $home = "'$home'";
    }
    $apartment = 'null'; // квартира
    if (!empty($orderArr['apartment'])) {
        $apartment = mysqli_real_escape_string(getConnection(), $orderArr['apartment']);
        $apartment = "'$apartment'";
    }

    $query = "insert into orders (`name`, surname, patronymic, delivery, pay, comment, product_id, `timestamp`, city, street, home, apartment, price, done, email, phone)
values ('$name', '$surname', $patronymic, '$delivery', '$pay', '$comment', '$productId', '$timeStamp', $city, $street, $home, $apartment, '$price', 0, '$email', '$phone')";

    if ($requestCreateOrder = mysqli_query(getConnection(), $query)) {
        return $requestCreateOrder;
    }
}

/**Функция - запрос информации о заказах
 * @return array - массив информации о заказах
 */
function getOrders()
{
    if ($requestOrders = mysqli_query(getConnection(),
        "select * from orders order by done ASC, `timestamp` DESC")) {
        return mysqli_fetch_all($requestOrders, MYSQLI_ASSOC);
    }
}

/**Функция - запрос на изменение статуса заказа
 * @param $orderArr - массив с данными для изменения статуса
 * @return bool|\mysqli_result - информация об успешном завершении запроса
 */
function changeStatus($orderArr)
{
    $id = mysqli_real_escape_string(getConnection(), $orderArr['id']); // идентификатор заказа
    $status = mysqli_real_escape_string(getConnection(), $orderArr['done']); // статус заказа
    if ($requestChangeStatus = mysqli_query(getConnection(),
        "update orders set done='$status' where id='$id'")) {
        return $requestChangeStatus;
    }
}

/**Функция - запрос на добавление нового товара
 * @param $productArr - массив с парамметрами товара
 * @param $imgName - имя файла с изображением товара
 * @return bool - результат запроса (true - успешный, false - нет)
 */
function addProduct($productArr, $imgName)
{
    $name = mysqli_real_escape_string(getConnection(), $productArr['productName']);
    $price = mysqli_real_escape_string(getConnection(), $productArr['productPrice']);
    $imgName = mysqli_real_escape_string(getConnection(), $imgName);
    $new = mysqli_real_escape_string(getConnection(), $productArr['new']);
    $sale = mysqli_real_escape_string(getConnection(), $productArr['sale']);
    $categoryRequest = 'values';
    foreach ($productArr['category'] as $category) {
        $categoryId = mysqli_real_escape_string(getConnection(), $category);
        $categoryRequest .= " ('$categoryId', last_insert_id()),";
    }
    $categoryRequest = substr_replace($categoryRequest, ';', -1);
    $multiQuery = "insert into products (`name`, price, img_name, `new`, sale) 
                    values ('$name', '$price', '$imgName', '$new', '$sale');
                insert into category_product (category_id, product_id) $categoryRequest";
    if ($requestAddProduct = mysqli_multi_query(getConnection(),
        $multiQuery)) {
        return $requestAddProduct;
    }
}

/**Функция - запрос на изменение информации о товаре
 * @param $productArr - массив с парамметрами товара
 * @param $imgName - имя файла с изображением товара
 * @return bool - результат запроса (true - успешный, false - нет)
 */
function changeProduct($productArr, $imgName)
{
    $id = (int)$productArr['id'];
    $name = mysqli_real_escape_string(getConnection(), $productArr['productName']);
    $price = mysqli_real_escape_string(getConnection(), $productArr['productPrice']);
    $new = mysqli_real_escape_string(getConnection(), $productArr['new']);
    $sale = mysqli_real_escape_string(getConnection(), $productArr['sale']);
    $imgName = mysqli_real_escape_string(getConnection(), $imgName);

    $categoryRequest = 'values';
    foreach ($productArr['category'] as $category) {
        $categoryId = mysqli_real_escape_string(getConnection(), $category);
        $categoryRequest .= " ('$categoryId', '$id'),";
    }
    $categoryRequest = substr_replace($categoryRequest, ';', -1);
    $multiQuery = "update products set `name` = '$name', price = '$price',img_name = '$imgName', `new` = '$new', sale = '$sale' where `id` = '$id';
                    delete from category_product where product_id = '$id';
                 insert into category_product (category_id, product_id) $categoryRequest";
    if ($requestChangeProduct = mysqli_multi_query(getConnection(),
        $multiQuery)) {
        return $requestChangeProduct;
    }
}

/**Функция - запрос на удаление товара
 * @param $productId - идентификатор товара
 * @return bool|\mysqli_result - информация об успешном завершении запроса
 */
function deleteProduct($productId)
{
    $id = (int)$productId; // идентификатор товара
    $multiQuery = "delete from products where id='$id';
                    delete from category_product where product_id = '$id'";
    if ($requestDeleteProduct = mysqli_multi_query(getConnection(),
        $multiQuery)) {
        return $requestDeleteProduct;
    }
}
