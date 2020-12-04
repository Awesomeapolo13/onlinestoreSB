<article class="shop__item product" tabindex="0">
    <div class="product__image">
        <img src="<?= $product['imgPath'] ?>" alt="<?= $product['name'] ?>">
    </div>
    <p class="product__name"><?= $product['name'] ?></p>
    <span class="product__price"><?= number_format($product['price'], 0, ' ', ' ') ?> руб.</span>
</article>
