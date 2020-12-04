<ul class="filter__list">
    <li>
        <a class="filter__list-item<?php if (pageHelper\isCurrentURL('/', true)): ?> active<?php endif; ?>" href="/">Все</a>
    </li>
    <?php foreach ($categories as $category): ?>
        <li>
            <a class="filter__list-item<?php if (isset($_GET['category']) && $_GET['category'] === $category['type']): ?> active<?php endif; ?>"
               href="<?= $category['path'] ?>"><?= $category['name'] ?></a>
        </li>
    <?php endforeach; ?>
</ul>
