<select name="category[]" class="custom-form__select" multiple="multiple">
    <option hidden="">Название раздела</option>
    <?php foreach ($categories as $category): ?>
        <option value="<?= $category['id'] ?>"
                <?php if (isset($_GET['categoryType']) && in_array($category['type'], $_GET['categoryType'], true)): ?>selected<?php endif; ?>><?= $category['name'] ?></option>
    <?php endforeach; ?>
</select>