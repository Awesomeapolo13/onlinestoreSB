<ul class="main-menu main-menu--<?= $menuClass ?>">
    <?php foreach ($menuArray as $menuItem): ?>
        <?php if ($menuItem['admin'] === $admin || $menuItem['admin'] === null) : ?>
            <li <?php if (empty($_SESSION['admin']) && $menuItem['title'] === 'Товары'): ?>hidden<?php endif; ?>>
                <a class="main-menu__item<?php if (pageHelper\isCurrentURL($menuItem['path'])): ?> active<?php endif; ?>"
                   href="<?= $menuItem['path'] ?>"><?= $menuItem['title'] ?></a>
            </li>
        <?php endif; ?>
    <?php endforeach;
    if (empty($_SESSION['isLoggedIn'])):?>
        <li>
            <a class="main-menu__item" href="/authorization">Войти</a>
        </li>
    <?php else: ?>
        <li>
            <a class="main-menu__item" href="/?login=out">Выйти</a>
        </li>
    <?php endif; ?>
</ul>
