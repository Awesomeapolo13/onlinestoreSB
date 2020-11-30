<?php
session_name('session_id'); //задаем ключь идентификатора сессии как session_id
session_start(); //открываем сессию
if (isset($_COOKIE['login'])) {
    setcookie('login', $_COOKIE['login'], time() + 43200, '/'); //продление куки с логином пользвателя
}
error_reporting(E_ALL);
//Файл с переменными для конфигурации контента на странице
include $_SERVER['DOCUMENT_ROOT'] . '/config/index.php';

// Выход
if (!empty($_GET['login']) && $_GET['login'] === 'out') {
    $isLoggedIn = null;
    session_unset();
    if (!empty($_SESSION['isLoggedIn'])) {
        unset($_SESSION['isLoggedIn']);
    }
    session_destroy();
    setcookie(session_name(), session_id(), time() - 3600, '/');
}

//Файл с функциями отображения контента
include $_SERVER['DOCUMENT_ROOT'] . '/helpers/pageHelper.php';
//Файл с функциями для запросов к БД
include $_SERVER['DOCUMENT_ROOT'] . '/helpers/requestDBHelper.php';
//Подключение хедера
include $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php';
//Функция отображения контента определеннной страницы
pageHelper\showContent($contentArray);
//Подключение футера
include $_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php';
