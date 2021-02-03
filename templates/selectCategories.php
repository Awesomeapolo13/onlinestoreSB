<select name="category[]" class="custom-form__select" multiple="multiple">
    <option hidden="">Название раздела</option>
    <?php foreach ($categories as $category): ?>
        <option value="<?= $category['id'] ?>"
                <?php if (isset($_POST['categoryType']) && in_array($category['type'], $_POST['categoryType'], true)): ?>selected<?php endif; ?>><?= $category['name'] ?></option>
    <?php endforeach; ?>
</select>