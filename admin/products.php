<?php
include $_SERVER['DOCUMENT_ROOT'] . '/config/index.php';

//Перечень продуктов
$products = pageHelper\transformProductsArr(requestDBHelper\getProducts());
?>
<main class="page-products">
    <h1 class="h h--1">Товары</h1>
    <a class="page-products__button button" href="/admin/add">Добавить товар</a>
    <div class="page-products__header">
        <span class="page-products__header-field">Название товара</span>
        <span class="page-products__header-field">ID</span>
        <span class="page-products__header-field">Цена</span>
        <span class="page-products__header-field">Категория</span>
        <span class="page-products__header-field">Новинка</span>
    </div>
    <ul class="page-products__list">
        <?php pageHelper\showAdminProducts($products); ?>
    </ul>
</main>
