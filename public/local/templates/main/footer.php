<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\App;
use App\View as v;
use App\Layout;
use Core\Env;
use App\View\FormMacros as m;

// bring context variables into scope
extract(App::getInstance()->layoutContext(), EXTR_SKIP);

if ($isAjax) {
    // skip the whole thing for ajax requests
    return;
}
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
                    <a class="footer-partners" href="<?= v::path('partneram') ?>">Партнерам</a>
                </div>
            </div>
            <div class="right">
                <ul>
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:menu",
                        "footer",
                        Array(
                            "ALLOW_MULTI_SELECT" => "N",
                            "CHILD_MENU_TYPE" => "left",
                            "DELAY" => "N",
                            "MAX_LEVEL" => "1",
                            "MENU_CACHE_GET_VARS" => array(""),
                            "MENU_CACHE_TIME" => "3600",
                            "MENU_CACHE_TYPE" => "N",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "ROOT_MENU_TYPE" => "top",
                            "USE_EXT" => "Y"
                        )
                    ); ?>
                    <li class="footer-service">
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            Array(
                                "AREA_FILE_SHOW" => "file",
                                "PATH" => v::includedArea('layout/footer_links.php')
                            )
                        ); ?>
                    </li>
                    <li class="footer-contacts">
                        <p class="footer-title">Контакты</p>
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            Array(
                                "AREA_FILE_SHOW" => "file",
                                "PATH" => v::includedArea('contact_info/footer.php')
                            )
                        ); ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="wrap">
            <span class="copy"><? $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => v::includedArea('layout/copyright.php')
                    )
                ); ?></span>
            <span class="create-by">Разработка сайта - <a href="https://i-market.ru" target="_blank">i-market.ru</a></span>
        </div>
    </div>
</footer>
<? // modals ?>

<? $APPLICATION->ShowViewContent('modals') ?>

<div class="modal" id="contact-modal">
    <div class="block">
        <span class="close close-modal">×</span>
        <p class="title">Напишите нам</p>
        <form action="/ajax/ajax.php?no_js=1" class="validate">
            <input type="hidden" name="mode" value="contact_form">
            <? m::showInput('NAME', 'Имя *', ['required' => true]) ?>
            <? m::showInput('PHONE', 'Контактный телефон *', ['required' => true]) ?>
            <? m::showInput('EMAIL', 'Контактный e-mail *', ['required' => true]) ?>
            <textarea name="MESSAGE" placeholder="Сообщение"><?= v::escAttr(v::get($_REQUEST, 'MESSAGE')) ?></textarea>
            <button type="submit" class="download-btn">Отправить</button>
        </form>
    </div>
</div>
<? if (!App::useBitrixAsset()): ?>
    <? foreach (App::assets()['scripts'] as $path): ?>
        <script src="<?= $path ?>"></script>
    <? endforeach ?>
<? endif ?>
<? if (App::env() === Env::DEV): ?>
    <script>
      $('img').one('error', function () {
        console.error('using placeholder img for:', this);
        // quickfix for the missing images
        this.src = '/content-examples/placeholder.png';
      });
    </script>
<? endif ?>
</body>
</html>
