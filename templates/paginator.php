<ul class="shop__paginator paginator">
    <li>
        <a class="paginator__item"
            <?php if ($page === $currentPage):
                null;
            else: ?>
           href="/?page=<?= $page; ?>&<?= $productsParams;
           endif; ?>"><?= $page ?></a>
    </li>
</ul>
