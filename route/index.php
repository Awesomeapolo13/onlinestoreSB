<?php
//Подключение файла с конфигурацией
include $_SERVER['DOCUMENT_ROOT'] . '/config/index.php';
//Запросы в БД
$categories = requestDBHelper\getCategories();
$products = pageHelper\transformProductsArr(requestDBHelper\getProducts());

//Сообщения об ошибках
$sortErrorMsg = '';

if (!empty($_GET)) {
    //Фильтр по цене и типу (новинка, распродажа)
    if (isset($_GET['submitFilter'])) {
        $products = pageHelper\filterProducts($products, $_GET);
    }
    //Фильтр по категориям
    if (isset($_GET['category'])) {
        $products = pageHelper\queryFilter($products, 'categoryTypes', $_GET['category'], true);
    }
    //Фильтр по категориям
    if (isset($_GET['new'])) {
        $products = pageHelper\queryFilter($products, 'new', $_GET['new']);
    }
    //Фильтр по категориям
    if (isset($_GET['sale'])) {
        $products = pageHelper\queryFilter($products, 'sale', $_GET['sale']);
    }
    //Сортировка
    if (isset($_GET['sortCategory']) && isset($_GET['orderCategory'])) {
        $products = pageHelper\sortProducts($products, $_GET['sortCategory'], $_GET['orderCategory']);
        $sortErrorMsg = '';
    } elseif (isset($_GET['sortCategory'])) {
        $sortErrorMsg = 'Выберите параметр для сортировки';
    } elseif (isset($_GET['orderCategory'])) {
        $sortErrorMsg = 'Выберите параметр порядок сортировки';
    }
}

// Колличество страниц необходимое для отображения товара
$pages = ceil(count($products) / 9);
//Определение номера текущей страницы
$page = $_GET['page'] ?? 1;
?>

<main class="shop-page">
    <header class="intro">
        <div class="intro__wrapper">
            <h1 class=" intro__title">COATS</h1>
            <p class="intro__info">Collection 2018</p>
        </div>
    </header>
    <section class="shop container">
        <section class="shop__filter filter">
            <!--            Параметры фильтрации-->
            <form id="filterForm">
                <div class="filter__wrapper">
                    <b class="filter__title">Категории</b>
                    <?php include $_SERVER['DOCUMENT_ROOT'] . '/templates/categories.php'; //вывод списка категорий товаров?>
                    <input type="hidden" name="category"
                           value="<?= isset($_GET['category']) ? htmlspecialchars($_GET['category']) : '' ?>">
                </div>
                <div class="filter__wrapper">
                    <b class="filter__title">Фильтры</b>
                    <div class="filter__range range">
                        <span class="range__info">Цена</span>
                        <div class="range__line" aria-label="Range Line"></div>
                        <div class="range__res">
                            <span class="range__res-item min-price"><?= isset($_GET['minPrice']) ? htmlspecialchars($_GET['minPrice']) : '350' ?> руб.</span>
                            <input type="hidden" class="input_min_price" name="minPrice"
                                   value="<?= isset($_GET['minPrice']) ? htmlspecialchars($_GET['minPrice']) : 350 ?>">
                            <span class="range__res-item max-price"><?= isset($_GET['maxPrice']) ? htmlspecialchars($_GET['maxPrice']) : '32000' ?> руб.</span>
                            <input type="hidden" class="input_max_price" name="maxPrice"
                                   value="<?= isset($_GET['maxPrice']) ? htmlspecialchars($_GET['maxPrice']) : 32000 ?>">
                        </div>
                    </div>
                </div>

                <fieldset class="custom-form__group">
                    <input type="checkbox" name="new" id="new" value="1"
                           class="custom-form__checkbox" <?php if (isset($_GET['new']) && $_GET['new'] === '1'): ?> checked<?php endif; ?>>
                    <label for="new" class="custom-form__checkbox-label custom-form__info" style="display: block;">Новинка</label>
                    <input type="checkbox" name="sale" id="sale" value="1"
                           class="custom-form__checkbox" <?php if (isset($_GET['sale']) && $_GET['sale'] === '1'): ?> checked<?php endif; ?>>
                    <label for="sale" class="custom-form__checkbox-label custom-form__info" style="display: block;">Распродажа</label>
                </fieldset>
                <button class="button" type="submit" name="submitFilter" value="submitFilter" style="width: 100%">
                    Применить
                </button>
            </form>
        </section>
        <!--        Параметры сортировки-->
        <div class="shop__wrapper">
            <section class="shop__sorting">
                <div class="shop__sorting-item custom-form__select-wrapper">
                    <select class="custom-form__select" name="sortCategory" form="filterForm">
                        <option hidden="" value="null">Сортировка</option>
                        <option value="price"
                                <?php if (isset($_GET['sortCategory']) && $_GET['sortCategory'] === 'price'): ?>selected<?php endif; ?>>
                            По цене
                        </option>
                        <option value="name"
                                <?php if (isset($_GET['sortCategory']) && $_GET['sortCategory'] === 'name'): ?>selected<?php endif; ?>>
                            По названию
                        </option>
                    </select>
                </div>
                <div class="shop__sorting-item custom-form__select-wrapper">
                    <select class="custom-form__select" name="orderCategory" form="filterForm">
                        <option hidden="" value="null">Порядок</option>
                        <option value="<?= SORT_ASC ?>"
                                <?php if (isset($_GET['orderCategory']) && $_GET['orderCategory'] == SORT_ASC): ?>selected<?php endif; ?>>
                            По возрастанию
                        </option>
                        <option value="<?= SORT_DESC ?>"
                                <?php if (isset($_GET['orderCategory']) && $_GET['orderCategory'] == SORT_DESC): ?>selected<?php endif; ?>>
                            По убыванию
                        </option>
                    </select>
                </div>
                <p class="shop__sorting-res">Найдено <span class="res-sort"><?= count($products) ?></span> моделей</p>
            </section>
            <p><?= $sortErrorMsg ?></p>
            <!--            Список товаров-->
            <section class="shop__list">
                <?php pageHelper\showProducts($products, $pages, $page); //вывод списка товаров ?>
            </section>
            <?php pageHelper\showPaginator($pages, $page); ?>
        </div>
    </section>
    <section class="shop-page__order" hidden="">
        <div class="shop-page__wrapper">
            <h2 class="h h--1">Оформление заказа</h2>
            <form id="addOrderForm" action="#" method="post" class="custom-form js-order">
                <fieldset class="custom-form__group">
                    <legend class="custom-form__title">Укажите свои личные данные</legend>
                    <p class="custom-form__info">
                        <span class="req">*</span> поля обязательные для заполнения
                    </p>
                    <div class="custom-form__column">
                        <label class="custom-form__input-wrapper" for="surname">
                            <input id="surname" class="custom-form__input" type="text" name="surname" required="">
                            <p class="custom-form__input-label">Фамилия <span class="req">*</span></p>
                        </label>
                        <label class="custom-form__input-wrapper" for="name">
                            <input id="name" class="custom-form__input" type="text" name="name" required="">
                            <p class="custom-form__input-label">Имя <span class="req">*</span></p>
                        </label>
                        <label class="custom-form__input-wrapper" for="thirdName">
                            <input id="thirdName" class="custom-form__input" type="text" name="patronymic">
                            <p class="custom-form__input-label">Отчество</p>
                        </label>
                        <label class="custom-form__input-wrapper" for="phone">
                            <input id="phone" class="custom-form__input" type="tel" name="phone" required="">
                            <p class="custom-form__input-label">Телефон <span class="req">*</span></p>
                        </label>
                        <label class="custom-form__input-wrapper" for="email">
                            <input id="email" class="custom-form__input" type="email" name="email" required="">
                            <p class="custom-form__input-label">Почта <span class="req">*</span></p>
                        </label>
                    </div>
                </fieldset>
                <fieldset class="custom-form__group js-radio">
                    <legend class="custom-form__title custom-form__title--radio">Способ доставки</legend>
                    <input id="dev-no" class="custom-form__radio" type="radio" name="delivery" value="dev-no"
                           checked>
                    <label for="dev-no" class="custom-form__radio-label">Самовывоз</label>
                    <input id="dev-yes" class="custom-form__radio" type="radio" name="delivery" value="dev-yes">
                    <label for="dev-yes" class="custom-form__radio-label">Курьерная доставка</label>
                </fieldset>
                <div class="shop-page__delivery shop-page__delivery--no">
                    <table class="custom-table">
                        <caption class="custom-table__title">Пункт самовывоза</caption>
                        <tr>
                            <td class="custom-table__head">Адрес:</td>
                            <td>Москва г, Тверская ул,<br> 4 Метро «Охотный ряд»</td>
                        </tr>
                        <tr>
                            <td class="custom-table__head">Время работы:</td>
                            <td>пн-вс 09:00-22:00</td>
                        </tr>
                        <tr>
                            <td class="custom-table__head">Оплата:</td>
                            <td>Наличными или банковской картой</td>
                        </tr>
                        <tr>
                            <td class="custom-table__head">Срок доставки:</td>
                            <td class="date">13 декабря—15 декабря</td>
                        </tr>
                    </table>
                </div>
                <div class="shop-page__delivery shop-page__delivery--yes" hidden="">
                    <fieldset class="custom-form__group">
                        <legend class="custom-form__title">Адрес</legend>
                        <p class="custom-form__info">
                            <span class="req">*</span> поля обязательные для заполнения
                        </p>
                        <div class="custom-form__row">
                            <label class="custom-form__input-wrapper" for="city">
                                <input id="city" class="custom-form__input" type="text" name="city">
                                <p class="custom-form__input-label">Город <span class="req">*</span></p>
                            </label>
                            <label class="custom-form__input-wrapper" for="street">
                                <input id="street" class="custom-form__input" type="text" name="street">
                                <p class="custom-form__input-label">Улица <span class="req">*</span></p>
                            </label>
                            <label class="custom-form__input-wrapper" for="home">
                                <input id="home" class="custom-form__input custom-form__input--small" type="text"
                                       name="home">
                                <p class="custom-form__input-label">Дом <span class="req">*</span></p>
                            </label>
                            <label class="custom-form__input-wrapper" for="apartment">
                                <input id="apartment" class="custom-form__input custom-form__input--small" type="text"
                                       name="apartment">
                                <p class="custom-form__input-label">Квартира <span class="req">*</span></p>
                            </label>
                        </div>
                    </fieldset>
                </div>

                <fieldset class="custom-form__group shop-page__pay">
                    <legend class="custom-form__title custom-form__title--radio">Способ оплаты</legend>
                    <input id="cash" class="custom-form__radio" type="radio" name="pay" value="cash">
                    <label for="cash" class="custom-form__radio-label">Наличные</label>
                    <input id="card" class="custom-form__radio" type="radio" name="pay" value="card" checked="">
                    <label for="card" class="custom-form__radio-label">Банковской картой</label>
                </fieldset>
                <fieldset class="custom-form__group shop-page__comment">
                    <legend class="custom-form__title custom-form__title--comment">Комментарии к заказу</legend>
                    <textarea class="custom-form__textarea" name="comment"></textarea>
                </fieldset>
                <!--             Информация о цене и идентификаторе товара-->
                <input type="hidden" class="productId" name="productId" value="">
                <input type="hidden" class="price" name="productPrice" value="">
                <p class="server-form-error"></p>
                <button class="button" type="submit" name="sendOrder" value="true">Отправить заказ</button>
                <p class="error" hidden>Заполните все обязательные поля</p>
            </form>
        </div>
    </section>
    <section class="shop-page__popup-end" hidden="">
        <div class="shop-page__wrapper shop-page__wrapper--popup-end">
            <h2 class="h h--1 h--icon shop-page__end-title">Спасибо за заказ!</h2>
            <p class="shop-page__end-message">Ваш заказ успешно оформлен, с вами свяжутся в ближайшее время</p>
            <button class="button">Продолжить покупки</button>
        </div>
    </section>
</main>
