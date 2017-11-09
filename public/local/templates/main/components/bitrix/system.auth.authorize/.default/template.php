<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
use App\View\FormMacros as m;

$APPLICATION->SetPageProperty('layout', 'bare');

$showHiddenInputs = function () use ($arResult) {
    ?>
    <input type="hidden" name="AUTH_FORM" value="Y" />
    <input type="hidden" name="TYPE" value="AUTH" />
    <?if (strlen($arResult["BACKURL"]) > 0):?>
        <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
    <?endif?>
    <?foreach ($arResult["POST"] as $key => $value):?>
        <input type="hidden" name="<?=$key?>" value="<?=$value?>" />
    <?endforeach?>
    <?
};
?>
<div class="modal-like form">
    <div class="block">
        <form name="form_auth" method="post" action="<?=$arResult["AUTH_URL"]?>">
            <div class="title">Войдите в аккаунт</div>
            <? $result = $arParams['~AUTH_RESULT'] ?>
            <? if (!v::isEmpty($result)): ?>
                <div class="form__message <?= $result['TYPE'] === 'ERROR' ? 'form__message--error' : '' ?>">
                    <?= $result['MESSAGE'] ?>
                </div>
            <? endif ?>
            <? if (!v::isEmpty($arResult['ERROR_MESSAGE'])): ?>
                <div class="form__message form__message--error">
                    <?= $arResult['ERROR_MESSAGE'] ?>
                </div>
            <? endif ?>
            <? $showHiddenInputs() ?>
            <? m::showInput('USER_LOGIN', 'Логин или e-mail') ?>
            <? m::showInput('USER_PASSWORD', 'Пароль', ['type' => 'password']) ?>
            <? // TODO newsletter ?>
            <div class="TODO-mockup wrap-checkbox">
                <input type="checkbox" hidden="hidden" id="1">
                <label for="1">Подписка на новости</label>
                <a class="forget" href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>">Забыли пароль?</a>
            </div>
            <button type="submit" class="download-btn">Войти</button>
            <p class="modal-text">Если у вас нет аккаунта, зарегистрируйтесь как <strong>покупатель</strong> или как <strong>сервисный центр</strong></p>
            <a class="yellow-btn" href="<?=$arResult["AUTH_REGISTER_URL"]?>">Зарегистрироваться</a>
        </form>
    </div>
</div>

