<?php
$categoryType = '';
foreach ($product['categoryTypes'] as $type):
    $categoryType .= "&categoryType[]=$type";
endforeach; ?>
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
    <a href="/admin/add?type=change&name=<?= $product['name'] ?>&price=<?= $product['price'] ?>&new=<?= $product['new'] ?>&sale=<?= $product['sale'] ?>&id=<?= $product['id'] ?>&oldPath=<?= $product['imgPath'] ?><?= $categoryType ?>"
       class="product-item__edit" aria-label="Редактировать"></a>
    <span class="error"></span>
    <button id="<?= $product['id'] ?>" class="product-item__delete"></button>
</li>
