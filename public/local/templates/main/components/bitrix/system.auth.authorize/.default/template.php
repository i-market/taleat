<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
use App\View\FormMacros as m;
use Core\Util;

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
<div class="modal-like">
    <div class="block">
        <form class="form validate" name="form_auth" method="post" action="<?=$arResult["AUTH_URL"]?>">
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
            <? m::showInput('USER_LOGIN', 'Логин или e-mail', ['required' => true]) ?>
            <? m::showInput('USER_PASSWORD', 'Пароль', ['required' => true, 'type' => 'password']) ?>
            <div class="wrap-checkbox">
                <? $id = 'remember-'.Util::uniqueId() ?>
                <input type="checkbox" hidden="hidden" name="USER_REMEMBER" value="Y" id="<?= $id ?>">
                <label for="<?= $id ?>">Запомнить меня</label>
                <a class="forget" href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>">Забыли пароль?</a>
            </div>
            <button type="submit" class="download-btn">Войти</button>
            <p class="modal-text">Если у вас нет аккаунта, зарегистрируйтесь как <strong>покупатель</strong> или как <strong>сервисный центр</strong></p>
            <a class="yellow-btn" href="<?=$arResult["AUTH_REGISTER_URL"]?>">Зарегистрироваться</a>
        </form>
    </div>
</div>

