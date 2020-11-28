<?php

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
        'path' => '/?new=yes',
        'sort' => 1,
        'admin' => false,
    ],
    [
        'title' => 'Sale',
        'path' => '/?sale=yes',
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
    [
        'title' => 'Выйти',
        'path' => '/?login=out',
        'sort' => 6,
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

//Массив главного меню
$mainMenu = [
    [
        'title' => 'Главная',
        'path' => '/',
        'sort' => 0,
        'admin' => null,
    ],
    [
        'title' => 'Новинки',
        'path' => '/?new=yes',
        'sort' => 1,
        'admin' => false,
    ],
    [
        'title' => 'Sale',
        'path' => '/?sale=yes',
        'sort' => 2,
        'admin' => false,
    ],
    [
        'title' => 'Доставка',
        'path' => '/delivery',
        'sort' => 3,
        'admin' => false,
    ],
];

//Массив меню для авторизованных оператора и администратора
$adminMenu = [
    [
        'title' => 'Главная',
        'path' => '/',
        'sort' => 0,
        'admin' => null,
    ],
    [
        'title' => 'Товары',
        'path' => '/admin/products',
        'sort' => 1,
        'admin' => true,
    ],
    [
        'title' => 'Заказы',
        'path' => '/admin/orders',
        'sort' => 2,
        'admin' => true,
    ],
];