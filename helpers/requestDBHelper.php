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