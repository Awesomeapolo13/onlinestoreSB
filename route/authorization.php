<?php
//Файл с переменными для конфигурации контента на странице
include $_SERVER['DOCUMENT_ROOT'] . '/config/index.php';
//Параметр указывающий на успешность авторизации
$isLoggedIn = null;
$authMsg = '';

if (!empty($_POST)) {
    $isLoggedIn = false;
    $authMsg = 'Проверьте логин и пароль!';
    $login = $_COOKIE['login'] ?? $_POST['login'];
    $password = $_POST['password'];
    $requestAuth = requestDBHelper\getUserByLogin($login);
    if (isset($requestAuth[0]['password'])) {
        if (password_verify($_POST['password'], $requestAuth[0]['password'])) {
            $isLoggedIn = true;
            $authMsg = 'Вы успешно авторизованы!';
            $_SESSION['isLoggedIn'] = true;
            setcookie('login', $login, time() + 43200, '/');
        }
    }
}
?>
<main class="page-authorization">
    <h1 class="h h--1">Авторизация</h1>
    <?php if ($isLoggedIn):
        include $_SERVER['DOCUMENT_ROOT'] . '/templates/authMsg.php'; ?>
    <p><a href="/admin/orders">В личный кабинет</a></p>
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
