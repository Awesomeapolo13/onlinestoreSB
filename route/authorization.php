<?php
//Файл с переменными для конфигурации контента на странице
include $_SERVER['DOCUMENT_ROOT'] . '/config/index.php';
//Параметр указывающий на успешность авторизации
$isLoggedIn = null;
$authMsg = '';
if (!empty($_POST)) {
    $isLoggedIn = false;
    $authMsg = 'Проверьте логин и пароль!';
    if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']) {
        $isLoggedIn = true;
        $authMsg = 'Вы успешно авторизованы!';
    }
}

?>
<main class="page-authorization">
    <h1 class="h h--1">Авторизация</h1>
    <?php if ($isLoggedIn):
        include $_SERVER['DOCUMENT_ROOT'] . '/templates/authMsg.php'; ?>
        <p><a href="/admin/orders">К списку заказов</a></p>
    <?php else: ?>
        <form class="custom-form" action="/authorization" method="post">
            <input type="email" class="custom-form__input" name="login" required=""
                   value=<?= isset($_POST['login']) ? htmlspecialchars($_POST['login']) : '' ?>>
            <input type="password" class="custom-form__input" name="password" required=""
                   value=<?= isset($_POST['login']) ? htmlspecialchars($_POST['password']) : '' ?>>
            <button class="button" type="submit">Войти в личный кабинет</button>
        </form>
        <?php if ($isLoggedIn === false):
            include $_SERVER['DOCUMENT_ROOT'] . '/templates/authMsg.php';
        endif;
    endif; ?>
</main>
