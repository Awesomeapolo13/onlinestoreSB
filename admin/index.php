<?php
session_name('session_id'); //задаем ключь идентификатора сессии как session_id
session_start(); //открываем сессию

if (isset($_COOKIE['login'])) {
    setcookie('login', $_COOKIE['login'], time() + 43200, '/'); // кродлеваем куки
}

//Файл с переменными для конфигурации контента на странице
include $_SERVER['DOCUMENT_ROOT'] . '/config/index.php';
//Файл с функциями отображения контента
include $_SERVER['DOCUMENT_ROOT'] . '/helpers/pageHelper.php';
//Файл с функциями для запросов к БД
include $_SERVER['DOCUMENT_ROOT'] . '/helpers/requestDBHelper.php';
//Подключение хедера
include $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php';

pageHelper\showContent($contentArray, true);

//Подключение футера
include $_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php';
