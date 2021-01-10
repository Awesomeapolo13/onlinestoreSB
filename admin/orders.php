<?php
include $_SERVER['DOCUMENT_ROOT'] . '/config/index.php';

//Перечень заказов
$orders = requestDBHelper\getOrders();
?>
<main class="page-order">
    <h1 class="h h--1">Список заказов</h1>
    <ul class="page-order__list">
        <?php pageHelper\showOrders($orders);?>
    </ul>
</main>
