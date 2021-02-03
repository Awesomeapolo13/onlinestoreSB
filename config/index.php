<?php

//Данные для подключения в БД
$dbHost = 'localhost';
$dbUser = 'root';
$dbPassword = 'root';
$dbName = 'onlinestoredb';


//Массив содержащий контент тегов title
$titleArray = [
    [
        'title' => 'Fashion',
        'path' => '/',
    ],
    [
        'title' => 'Авторизация',
        'path' => '/authorization',
    ],
    [
        'title' => 'Товары',
        'path' => '/admin/products',
    ],
    [
        'title' => 'Добавление товара',
        'path' => '/admin/add',
    ],
    [
        'title' => 'Доставка',
        'path' => '/delivery',
    ],
    [
        'title' => 'Список заказова',
        'path' => '/admin/orders',
    ],
];

//Массив меню
$menuArray = [
    [
        'title' => 'Главная',
        'path' => '/',
        'sort' => 0,
        'admin' => null,
    ],
    [
        'title' => 'Новинки',
        'path' => '/?new=1',
        'sort' => 1,
        'admin' => false,
    ],
    [
        'title' => 'Sale',
        'path' => '/?sale=1',
        'sort' => 2,
        'admin' => false,
    ],
    [
        'title' => 'Доставка',
        'path' => '/delivery',
        'sort' => 3,
        'admin' => false,
    ],
    [
        'title' => 'Товары',
        'path' => '/admin/products',
        'sort' => 4,
        'admin' => true,
    ],
    [
        'title' => 'Заказы',
        'path' => '/admin/orders',
        'sort' => 5,
        'admin' => true,
    ],
];

//Массив контента
$contentArray = [
    [
        'title' => 'Главная',
        'path' => '/',
    ],
    [
        'title' => 'Доставка',
        'path' => '/delivery',
    ],
    [
        'title' => 'Авторизация',
        'path' => '/authorization',
    ],
    [
        'title' => 'Товары',
        'path' => '/admin/products',
    ],
    [
        'title' => 'Заказы',
        'path' => '/admin/orders',
    ],
    [
        'title' => 'Добавление товара',
        'path' => '/admin/add',
    ],
];

//Конфигурация доставки

$standardDeliveryPrice = 280;
$onPurchaseDayDeliveryPrice = 560;
$deliveryWithFittingPrice = 280;
$limitFreeDelivery = 2000;

//Путь к папке с изображениями товаров
$uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/img/products/';

//Массив форматов изображений, которые  разрешены к загрузке
$imgTypesArr = [
    'image/jpeg',
    'image/jpg',
    'image/png'
];

//Ассоциативный массив групп пользователей
$groups = [
    'Оператор' => 'operator',
    'Администратор' => 'admin',
];
