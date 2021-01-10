<?php
session_name('session_id'); //задаем ключь идентификатора сессии как session_id
session_start(); //открываем сессию

//Файл с переменными для конфигурации контента на странице
include $_SERVER['DOCUMENT_ROOT'] . '/config/index.php';
//Файл с функциями отображения контента
include $_SERVER['DOCUMENT_ROOT'] . '/helpers/pageHelper.php';
//Файл с функциями для запросов к БД
include $_SERVER['DOCUMENT_ROOT'] . '/helpers/requestDBHelper.php';

//Редирект незарегистрированного пользователя на главную страницу
if (!empty($_SESSION['isLoggedIn'])) {
    pageHelper\isAuth($_SESSION['isLoggedIn'], '/admin/orders', '/');
    pageHelper\isAuth($_SESSION['isLoggedIn'], '/admin/products', '/');
    //Редирект пользователя, который не является администратором или оператором
    if (!empty($_SESSION['operator']) || !empty($_SESSION['admin'])) {
        pageHelper\isAuth($_SESSION['operator'], '/admin/orders', '/');
    } else {
        header("Location: /");
    }
    //Редирект пользователя, который не является администратором
    if (!empty($_SESSION['admin'])) {
        pageHelper\isAuth($_SESSION['admin'], '/admin/products', '/');
    } elseif (pageHelper\isCurrentURL('/admin/products')) {
        header("Location: /"); //Редирект для пользователей не подходящих под признак;
    }
} else {
    header("Location: /");
}

//Подключение хедера
include $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php';

pageHelper\showContent($contentArray, true);

//Подключение футера
include $_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php';
