<li class="order-item page-order__item">
    <div class="order-item__wrapper">
        <div class="order-item__group order-item__group--id">
            <span class="order-item__title">Номер заказа</span>
            <span class="order-item__info order-item__info--id"><?= $order['id'] ?></span>
        </div>
        <div class="order-item__group">
            <span class="order-item__title">Сумма заказа</span>
            <?= number_format($order['price'], 0, ' ', ' ') ?> руб.
        </div>
        <button class="order-item__toggle"></button>
    </div>
    <div class="order-item__wrapper">
        <div class="order-item__group order-item__group--margin">
            <span class="order-item__title">Заказчик</span>
            <span class="order-item__info"><?= $order['surname'] . ' ' . $order['name'] . ' ' . $order['patronymic'] ?></span>
        </div>
        <div class="order-item__group">
            <span class="order-item__title">Номер телефона</span>
            <span class="order-item__info"><?= $order['phone'] ?></span>
        </div>
        <div class="order-item__group">
            <span class="order-item__title">Способ доставки</span>
            <span class="order-item__info"><?= $order['delivery'] === 'dev-no' ? 'Самовывоз' : 'Курьерная доставка' ?></span>
        </div>
        <div class="order-item__group">
            <span class="order-item__title">Способ оплаты</span>
            <span class="order-item__info"><?= $order['pay'] === 'cash' ? 'Наличными' : 'Банковской картой' ?></span>
        </div>
        <div class="order-item__group order-item__group--status">
            <span class="order-item__title">Статус заказа</span>
            <span class="order-item__info order-item__info--<?= $order['done'] ? 'yes' : 'no' ?>"><?= $order['done'] ? 'Выполнено' : 'Не выполнено' ?></span>
            <button id="<?= $order['id'] ?>" class="order-item__btn">Изменить</button>
        </div>
    </div>
    <div class="order-item__wrapper">
        <div class="order-item__group">
            <span class="order-item__title">Адрес доставки</span>
            <?php if ($order['delivery'] === 'dev-no'): ?>
                <span class="order-item__info">г. Москва, ул. Пушкина, д.5, кв. 233</span>
            <?php else: ?>
                <span class="order-item__info">г. <?= $order['city'] ?>, ул. <?= $order['street'] ?>, д.<?= $order['home'] ?>, кв. <?= $order['apartment'] ?></span>
            <?php endif; ?>
        </div>
    </div>
    <div class="order-item__wrapper">
        <div class="order-item__group">
            <span class="order-item__title">Комментарий к заказу</span>
            <span class="order-item__info"><?= $order['comment'] ?></span>
        </div>
    </div>
</li>
