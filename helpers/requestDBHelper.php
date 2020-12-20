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
        "select email, password from users where email='$loginMySQL'")) {
        return mysqli_fetch_all($requestAuth, MYSQLI_ASSOC);
    }
}

/** Функция - запрос информации о товарах и их категориях
 * @return array - массив с информацией о товарах и их категориях
 */
function getProducts()
{
    if ($requestAuth = mysqli_query(getConnection(),
        "select products.id, products.name, products.price, products.img_path as 'imgPath', products.new, products.sale, c.type as 'categoryType' from products
left join categories c on products.category_id = c.id;")) {
        return mysqli_fetch_all($requestAuth, MYSQLI_ASSOC);
    }
}

/**Функция - запрос имеющихся категорий
 * @return array - массив категорий товаров
 */
function getCategories()
{
    if ($requestAuth = mysqli_query(getConnection(),
        "select * from categories;")) {
        return mysqli_fetch_all($requestAuth, MYSQLI_ASSOC);
    }
}

/**Функция - запрос на добавление заказа
 * @param $orderArr
 * @return bool|\mysqli_result
 */
function createOrder($orderArr)
{
    $requestString = "";
    $name = mysqli_real_escape_string(getConnection(), $orderArr['name']); // имя заказчика
    $surname = mysqli_real_escape_string(getConnection(), $orderArr['surname']); // фамилия заказчика
    empty($orderArr['patronymic']) ? $patronymic = null : $patronymic = mysqli_real_escape_string(getConnection(), $orderArr['patronymic']); // отчество заказчика
    $delivery = mysqli_real_escape_string(getConnection(), $orderArr['delivery']); // тип доставки
    $pay = mysqli_real_escape_string(getConnection(), $orderArr['pay']); // способ оплаты
    $comment = mysqli_real_escape_string(getConnection(), $orderArr['comment']); // тклкфон заказчика
    $productId = mysqli_real_escape_string(getConnection(), $orderArr['productId']); // идентификатор товара
    $timeStamp = date("Y-m-d H:i:s"); // время отправления
    empty($orderArr['city']) ? $city = 'NULL' : $city = mysqli_real_escape_string(getConnection(), $orderArr['city']); // город
    empty($orderArr['street']) ? $street = 'NULL' : $street = mysqli_real_escape_string(getConnection(), $orderArr['street']); // улица
    empty($orderArr['home']) ? $home = 'NULL' : $home = mysqli_real_escape_string(getConnection(), $orderArr['home']); // дом
    empty($orderArr['apartment']) ? $apartment = 'NULL' : $apartment = mysqli_real_escape_string(getConnection(), $orderArr['apartment']); // квартира
    $price = mysqli_real_escape_string(getConnection(), $orderArr['orderPrice']); // цена заказа с учетом стоимости доставки
    $email = mysqli_real_escape_string(getConnection(), $orderArr['email']); // электронная почта заказчика
    $phone = mysqli_real_escape_string(getConnection(), $orderArr['phone']); // тклкфон заказчика

    if ($requestCreateOrder = mysqli_query(getConnection(),
        "insert into orders (`name`, surname, patronymic, delivery, pay, comment, product_id, `timestamp`, city, street, home, apartment, price, done, email, phone)
values ('$name', '$surname', '$patronymic', '$delivery', '$pay', '$comment', '$productId', '$timeStamp', '$city', '$street', '$home', '$apartment', '$price', 0, '$email', '$phone')")) {
        return $requestCreateOrder;
    }
}