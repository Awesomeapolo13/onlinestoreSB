<?php
session_name('session_id'); //задаем ключь идентификатора сессии как session_id
session_start(); //открываем сессию

error_reporting(E_ALL);
//Файл с переменными для конфигурации контента на странице
include $_SERVER['DOCUMENT_ROOT'] . '/config/index.php';

//Файл с функциями для запросов к БД
include $_SERVER['DOCUMENT_ROOT'] . '/helpers/requestDBHelper.php';
//Авторизация
if (!empty($_POST)) {
    $login = $_POST['login'];
    $password = $_POST['password'];
    $requestAuth = requestDBHelper\getUserByLogin($login);
    if (isset($requestAuth[0]['password'])) {
        if (password_verify($_POST['password'], $requestAuth[0]['password'])) {
            $_SESSION['isLoggedIn'] = true;
            $requestGroups = requestDBHelper\getUserGroup($requestAuth[0]['id']);
            //Запись ролей пользоваетля в сессию (если они есть)
            foreach ($groups as $name => $type) {
                foreach ($requestGroups as $requestGroup) {
                    if (in_array($name, $requestGroup)) {
                        $_SESSION["$type"] = true;
                    }
                }
            }
            setcookie('login', $login, time() + 43200, '/');
        }
    }
}

//Продление куки с логином пользвателя
if (isset($_COOKIE['login'])) {
    setcookie('login', $_COOKIE['login'], time() + 43200, '/');
}

// Выход
if (!empty($_GET['login']) && $_GET['login'] === 'out') {
    $isLoggedIn = null;
    session_unset();
    if (!empty($_SESSION['isLoggedIn'])) {
        unset($_SESSION['isLoggedIn']);
        unset($_SESSION['admin']);
        unset($_SESSION['operator']);
    }
    session_destroy();
    setcookie(session_name(), session_id(), time() - 3600, '/');
}

//Файл с функциями отображения контента
include $_SERVER['DOCUMENT_ROOT'] . '/helpers/pageHelper.php';

//Подключение хедера
include $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php';
//Функция отображения контента определеннной страницы
pageHelper\showContent($contentArray);
//Подключение футера
include $_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php';
