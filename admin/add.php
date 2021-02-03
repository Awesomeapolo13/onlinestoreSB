<?php
include $_SERVER['DOCUMENT_ROOT'] . '/config/index.php';
if (!empty($_GET['type']) && $_GET['type'] === 'change') {
    $imgSrc = pageHelper\getBase64CodeImg($_SERVER['DOCUMENT_ROOT'] . '/img/products/' . $_POST['oldImg']);
}

$categories = requestDBHelper\getCategories();
?>
<main class="page-add">
    <h1 class="h h--1"><?= (!empty($_GET['type']) && $_GET['type'] === 'change') ? 'Изменение' : 'Добавление' ?>
        товара</h1>
    <form id="addProductForm" class="custom-form" enctype="multipart/form-data" action="#" method="post">
        <fieldset class="page-add__group custom-form__group">
            <legend class="page-add__small-title custom-form__title">Данные о товаре</legend>
            <label for="product-name" class="custom-form__input-wrapper page-add__first-wrapper">
                <input type="text" class="custom-form__input" name="productName" id="product-name" placeholder="Название товара"
                       value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
            </label>
            <label for="product-price" class="custom-form__input-wrapper">
                <input type="text" class="custom-form__input" name="productPrice" id="product-price" placeholder="Цена товара"
                       value="<?= isset($_POST['price']) ? htmlspecialchars($_POST['price']) : '' ?>">
            </label>
        </fieldset>
        <fieldset class="page-add__group custom-form__group">
            <legend class="page-add__small-title custom-form__title">Фотография товара</legend>
            <ul class="add-list">
                <li class="add-list__item add-list__item--add">
                    <input type="file" name="productImg" id="productPhoto" hidden=""
                           accept="image/jpeg,image/png,image/jpg">
                    <label for="productPhoto">Добавить фотографию</label>
                </li>
                <?php if (!empty($_GET['type']) && $_GET['type'] === 'change'): ?>
                    <li class="add-list__item add-list__item--active">
                        <img src="<?= $imgSrc ?>">
                    </li>
                <?php endif; ?>
            </ul>
        </fieldset>
        <fieldset class="page-add__group custom-form__group">
            <legend class="page-add__small-title custom-form__title">Раздел</legend>
            <div class="page-add__select">
                <?php include $_SERVER['DOCUMENT_ROOT'] . '/templates/selectCategories.php' ?>
            </div>
            <input type="checkbox" name="new" id="new" class="custom-form__checkbox" value="1"
                <?php if (isset($_POST['new']) && $_POST['new'] === '1'): ?> checked<?php endif; ?>>
            <label for="new" class="custom-form__checkbox-label">Новинка</label>
            <input type="checkbox" name="sale" id="sale" class="custom-form__checkbox" value="1"
                <?php if (isset($_POST['sale']) && $_POST['sale'] === '1'): ?> checked<?php endif; ?>>
            <label for="sale" class="custom-form__checkbox-label">Распродажа</label>
        </fieldset>
<!--     Скрытые поля, которые передаются, если нужно изменить информацию о товаре-->
        <?php if (!empty($_POST['oldImg']) && !empty($_POST['id'])):?>
            <input id="oldImg" type="hidden" name="oldImg" value="<?= $_POST['oldImg'] ?>">
            <input type="hidden" name="id" value="<?= $_POST['id'] ?>">
            <input type="hidden" name="admin" value="<?= $_SESSION['admin'] ?>">
        <?php endif; ?>
        <?php if (!empty($_SESSION['admin']) && $_SESSION['admin']):?>
            <input type="hidden" name="admin" value="<?= $_SESSION['admin'] ?>">
        <?php endif; ?>
        <button class="button"
                type="submit" name="<?= (!empty($_GET['type']) && $_GET['type'] === 'change') ? 'changeProduct' : 'addProduct' ?>"
                value="yes"><?= (!empty($_GET['type']) && $_GET['type'] === 'change') ? 'Изменить' : 'Добавить' ?> товар
        </button>
        <p class="error"></p>
    </form>
    <section class="shop-page__popup-end page-add__popup-end" hidden="">
        <div class="shop-page__wrapper shop-page__wrapper--popup-end">
            <h2 class="h h--1 h--icon shop-page__end-title">Товар
                успешно <?= (!empty($_GET['type']) && $_GET['type'] === 'change') ? 'изменен' : 'добавлен' ?></h2>
        </div>
        <div>
            <a class="page-products__button button" href="/admin/products">К списку товаров</a>
        </div>
    </section>
</main>
