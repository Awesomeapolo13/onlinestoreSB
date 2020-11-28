<?php
include $_SERVER['DOCUMENT_ROOT'] . '/config/index.php';
include $_SERVER['DOCUMENT_ROOT'] . '/templates/headerMenuUser.php'
?>
<main class="page-authorization">
    <h1 class="h h--1">Авторизация</h1>
    <form class="custom-form" action="#" method="post">
        <input type="email" class="custom-form__input" required="">
        <input type="password" class="custom-form__input" required="">
        <button class="button" type="submit">Войти в личный кабинет</button>
    </form>
</main>
