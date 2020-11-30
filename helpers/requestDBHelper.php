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