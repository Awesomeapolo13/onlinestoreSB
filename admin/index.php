<?php
//Файл с функциями отображения контента
include $_SERVER['DOCUMENT_ROOT'] . '/helpers/pageHelper.php';
//Файл с переменными для конфигурации контента на странице
include $_SERVER['DOCUMENT_ROOT'] . '/config/index.php';
//Подключение хедера
include $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php';

pageHelper\showContent($contentArray, true);

//Подключение футера
include $_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php';
