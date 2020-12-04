<footer class="page-footer">
    <div class="container">
        <a class="page-footer__logo" href="/">
            <img src="/img/logo--footer.svg" alt="Fashion">
        </a>
        <nav class="page-footer__menu">
            <?= pageHelper\showMenu($menuArray, null) //отображение меню футера?>
        </nav>
        <address class="page-footer__copyright">
            © Все права защищены
        </address>
    </div>
    <?php mysqli_close(requestDBHelper\getConnection()); //закрываем соединение с БД?>
</footer>
</body>
</html>
