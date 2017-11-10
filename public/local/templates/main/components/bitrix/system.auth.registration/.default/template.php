<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
use App\View\FormMacros as m;
use Core\Util;

$APPLICATION->SetPageProperty('layout', 'bare');

$showHiddenInputs = function () use ($arResult) {
    if (strlen($arResult["BACKURL"]) > 0)
    {
        ?>
        <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
        <?
    }
    ?>
    <input type="hidden" name="AUTH_FORM" value="Y" />
    <input type="hidden" name="TYPE" value="REGISTRATION" />
    <?
};
?>
<div class="modal-like form">
    <div class="block">
        <div class="title">Регистрация</div>
        <div class="modal-tab-links">
            <span data-tabLinks="customer" class="active">Я покупатель</span>
            <span data-tabLinks="service-center">Сервисный центр</span>
        </div>
        <div class="tab_blocks">
            <div data-tabContent="customer">
                <? $result = $arParams['~AUTH_RESULT'] ?>
                <? if (!v::isEmpty($result)): ?>
                    <div class="form__message <?= $result['TYPE'] === 'ERROR' ? 'form__message--error' : '' ?>">
                        <?= $result['MESSAGE'] ?>
                    </div>
                <? endif ?>
                <form method="post" action="<?=$arResult["AUTH_URL"]?>" name="bform" enctype="multipart/form-data">
                    <? $showHiddenInputs() ?>
                    <? // TODO validate name ?>
                    <? m::showInput('USER_LAST_NAME', 'Фамилия *') ?>
                    <? m::showInput('USER_NAME', 'Имя *') ?>
                    <? m::showInput('SECOND_NAME', 'Отчество') ?>
                    <? m::showInput('USER_EMAIL', 'E-mail *') ?>
                    <? m::showInput('USER_PASSWORD', 'Пароль', ['type' => 'password']) ?>
                    <? m::showInput('USER_CONFIRM_PASSWORD', 'Повторите пароль', ['type' => 'password']) ?>
                    <div class="TODO-mockup wrap-checkbox">
                        <? $id = 'input-'.Util::uniqueId() ?>
                        <? // TODO validate legal ?>
                        <input type="checkbox" hidden="hidden" id="<?= $id ?>">
                        <label for="<?= $id ?>">Даю согласие<br>на обработку персональных данных </label>
                    </div>
                    <? // TODO captcha ?>
                    <div class="TODO-mockup captcha">
                        <img src="<?= v::asset('images/ico/captcha.png') ?>" alt="">
                    </div>
                    <button type="submit" class="download-btn">Зарегистрироваться</button>
                </form>
            </div>
            <div data-tabContent="service-center">
                <? // TODO register service center ?>
                <input class="input" type="password" placeholder="Фамилия, Имя, Отчество">
                <input class="input" type="password" placeholder="Компания">
                <input class="input" type="password" placeholder="Город">
                <input class="input" type="password" placeholder="Телефон">
                <input class="input" type="password" placeholder="E-mail">
                <input class="input" type="password" placeholder="Пароль">
                <input class="input" type="password" placeholder="Повторите пароль">
            </div>
        </div>
    </div>
</div>