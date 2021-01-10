<ul class="shop__paginator paginator">
    <?php for ($page = 1; $page <= $pages; $page++) : ?>
        <li>
            <a class="paginator__item"
                <?php if ($page === $currentPage):
                    null;
                else: ?>
               href="/?page=<?= $page; ?>&<?= $changedPageParams;
               endif; ?>"><?= $page ?></a>
        </li>
    <?php endfor; ?>
</ul>
