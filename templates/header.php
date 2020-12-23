<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title><?= pageHelper\showHeadTitle($titleArray) ?></title>

    <meta name="description" content="Fashion - интернет-магазин">
    <meta name="keywords" content="Fashion, интернет-магазин, одежда, аксессуары">

    <meta name="theme-color" content="#393939">
    <?php if (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) == '/'): ?>
        <link rel="preload" href="/img/intro/coats-2018.jpg" as="image">
    <?php endif; ?>
    <link rel="preload" href="/fonts/opensans-400-normal.woff2" as="font">
    <link rel="preload" href="/fonts/roboto-400-normal.woff2" as="font">
    <link rel="preload" href="/fonts/roboto-700-normal.woff2" as="font">

    <link rel="icon" href="/img/favicon.png">
    <link rel="stylesheet" href="/css/style.min.css">

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="/js/scripts.js" defer=""></script>
</head>
<body>
<header class="page-header">
    <a class="page-header__logo" href="/">
        <img src="/img/logo.svg" alt="Fashion">
    </a>
    <nav class="page-header__menu">
        <?php
        if (isset($_SESSION['isLoggedIn'])):
            pageHelper\showMenu($menuArray, $_SESSION['isLoggedIn'], 'header');
        else:
            pageHelper\showMenu($menuArray, null, 'header');
        endif;
        ?>
    </nav>
</header>
