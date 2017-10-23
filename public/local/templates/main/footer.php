<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\App;
use App\View as v;
use App\Layout;

extract(App::getInstance()->layoutContext(), EXTR_SKIP);
?>
<? v::showForLayout('default', function () { ?>
    <? Layout::showDefaultPageWrapper('footer') ?>
<? }) ?>
</main>
<footer class="footer">
    <div class="footer-top">
        <div class="wrap">
            <div class="left">
                <a href="<?= v::path('/') ?>" class="footer-logo">
                    <img src="<?= v::asset('images/ico/footer-logo.png') ?>" alt="">
                </a>
                <div>
                    <a class="footer-partners" href="#">Партнерам</a>
                </div>
            </div>
            <div class="right">
                <ul>
                    <li class="footer-links">
                        <p><a href="#">Babyliss Paris</a></p>
                        <p><a href="#">Babyliss Paris</a></p>
                        <p><a href="#">Delonghi</a></p>
                        <p><a href="#">Kenwood</a></p>
                        <p><a href="#">Moser</a></p>
                    </li>
                    <li class="footer-service">
                        <p class="footer-title">сервисное обслуживание</p>
                        <div class="footer-line"></div>
                        <div class="footer-links">
                            <p><a href="#">Babyliss Paris</a></p>
                            <p><a href="#">Babyliss Paris</a></p>
                            <p><a href="#">Delonghi</a></p>
                        </div>
                    </li>
                    <li class="footer-contacts">
                        <p class="footer-title">Контакты</p>
                        <p class="footer-contacts-line">e-mail: <a href="mailto:asn@taleat.ru">asn@taleat.ru</a></p>
                        <p class="footer-contacts-line">Адрес фактический: <br>127473, г. Москва, <br>ул. Селезневская, д. 30 корп. 1</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="wrap">
            <span class="copy">ООО «ТАЛЕАТ-СЕРВИС» Все права защищены © <?= date('Y') ?> </span>
            <span class="create-by">Разработка сайта - <a href="https://i-market.ru" target="_blank">i-market.ru</a></span>
        </div>
    </div>
</footer>
<? $APPLICATION->ShowViewContent('modals') ?>
<? if (!App::useBitrixAsset()): ?>
    <? foreach (App::assets()['scripts'] as $path): ?>
        <script src="<?= $path ?>"></script>
    <? endforeach ?>
<? endif ?>
</body>
</html>
