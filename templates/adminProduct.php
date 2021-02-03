<li class="product-item page-products__item">
    <b class="product-item__name"><?= $product['name'] ?></b>
    <span id="productId" class="product-item__field"><?= $product['id'] ?></span>
    <span class="product-item__field"><?= number_format($product['price'], 0, ' ', ' ') ?> руб.</span>
    <span class="product-item__field">
        <?php foreach ($product['categoryNames'] as $name):
            echo $name ?>
            <br>
        <?php endforeach; ?></span>
    <span class="product-item__field"><?= $product['new'] ? 'Да' : 'Нет' ?></span>
    <form action="/admin/add?type=change&id=<?= $product['id'] ?>" method="post">
        <input type="hidden" name="id" value="<?= $product['id'] ?>">
        <input type="hidden" name="name" value="<?= $product['name'] ?>">
        <input type="hidden" name="price" value="<?= $product['price'] ?>">
        <input type="hidden" name="new" value="<?= $product['new'] ?>">
        <input type="hidden" name="sale" value="<?= $product['sale'] ?>">
        <input class="imgName" type="hidden" name="oldImg" value="<?= $product['imgName'] ?>">
        <select name="categoryType[]" multiple="multiple" hidden>
            <?php foreach ($product['categoryTypes'] as $type): ?>
                <option value="<?= $type ?>" selected></option>
            <?php endforeach; ?>
        </select>
            <input type="submit" class="product-item__edit" name="change" value="">
    </form>
    <span class="error"></span>
    <button id="<?= $product['id'] ?>" class="product-item__delete"></button>
</li>
