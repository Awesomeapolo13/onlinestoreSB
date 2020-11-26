<?php

//Файл с функциями отображения контента
include $_SERVER['DOCUMENT_ROOT'] . '/helpers/pageHelper.php';
//Файл с переменными для конфигурации контента на странице
include $_SERVER['DOCUMENT_ROOT'] . '/config/index.php';

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title><?= pageHelper\showHeadTitle($titleArray) ?></title>

    <meta name="description" content="Fashion - интернет-магазин">
    <meta name="keywords" content="Fashion, интернет-магазин, одежда, аксессуары">

    <meta name="theme-color" content="#393939">
    <?php if (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) == '/'): ?>
        <link rel="preload" href="img/intro/coats-2018.jpg" as="image">
    <?php endif; ?>
    <link rel="preload" href="/fonts/opensans-400-normal.woff2" as="font">
    <link rel="preload" href="/fonts/roboto-400-normal.woff2" as="font">
    <link rel="preload" href="/fonts/roboto-700-normal.woff2" as="font">

    <link rel="icon" href="/img/favicon.png">
    <link rel="stylesheet" href="/css/style.min.css">
    <?php if (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) == '/'): ?>
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <?php endif; ?>
    <script src="/js/scripts.js" defer=""></script>
</head>
<body>