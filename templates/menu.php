<ul class="main-menu main-menu--<?= $menuClass ?>">
    <?php foreach ($menuArray as $menuItem): ?>
        <?php if ($menuItem['admin'] === $admin || $menuItem['admin'] === null) : ?>
            <li>
                <a class="main-menu__item<?php if (pageHelper\isCurrentURL($menuItem['path'])): ?> active<?php endif; ?>"
                   href="<?= $menuItem['path'] ?>"><?= $menuItem['title'] ?></a>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>
