<article class="shop__item product" tabindex="0" id="<?= $product['id'] ?>">
    <div class="product__image">
        <img src="<?= $product['imgPath'] ?>" alt="<?= $product['name'] ?>">
    </div>
    <p class="product__name">
        <a href="/?productId=<?= $product['id'] ?>&productPrice=<?= $product['price'] ?>&<?= parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) ?>"
           style="color: #787B7F !important;">
            <?= $product['name'] ?></a>
    </p>
    <span class="product__price"><?= number_format($product['price'], 0, ' ', ' ') ?> руб.</span>
    <input type="hidden" class="productId" name="productId" value="<?= $product['id'] ?>">
    <input type="hidden" class="price" name="price" value="<?= $product['price'] ?>">
</article>
