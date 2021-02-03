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
    <?php if (!empty($_SESSION['admin']) && $_SESSION['admin']):?>
        <input id="isAdmin" type="hidden" name="admin" value="<?= $_SESSION['admin'] ?>">
    <?php endif; ?>
    <?php if (!empty($_SESSION['operator']) && $_SESSION['operator']):?>
        <input id="isOperator" type="hidden" name="operator" value="<?= $_SESSION['operator'] ?>">
    <?php endif; ?>
</main>
