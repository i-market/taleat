<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
use App\View\FormMacros as m;

$APPLICATION->SetPageProperty('layout', 'bare');

$showHiddenInputs = function () use ($arResult) {
    ?>
    <?if (strlen($arResult["BACKURL"]) > 0): ?>
        <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
    <? endif ?>
    <input type="hidden" name="AUTH_FORM" value="Y">
    <input type="hidden" name="TYPE" value="CHANGE_PWD">
    <?
};
?>
<? // TODO if arResult.USE_CAPTCHA ?>
<div class="modal-like">
    <div class="block">
        <form class="form validate" name="bform" method="post" action="<?=$arResult["AUTH_FORM"]?>">
            <div class="title">Изменение пароля</div>
            <? $result = $arParams['~AUTH_RESULT'] ?>
            <? if (!v::isEmpty($result)): ?>
                <div class="form__message <?= $result['TYPE'] === 'ERROR' ? 'form__message--error' : 'form__message--success' ?>">
                    <?= $result['MESSAGE'] ?>
                </div>
            <? else: ?>
                <? $showHiddenInputs() ?>
                <? m::showInput('USER_LOGIN', 'Логин', ['required' => true]) ?>
                <? if (!v::isEmpty($arResult['USER_CHECKWORD']) && v::get($result, 'TYPE') !== 'ERROR'): ?>
                    <input type="hidden" name="USER_CHECKWORD" value="<?= $arResult['USER_CHECKWORD'] ?>">
                <? else: ?>
                    <? m::showInput('USER_CHECKWORD', 'Контрольная строка', ['required' => true]) ?>
                <? endif ?>
                <? m::showInput('USER_PASSWORD', 'Новый пароль', ['required' => true, 'type' => 'password']) ?>
                <? // TODO add js validation for password confirmation ?>
                <? m::showInput('USER_CONFIRM_PASSWORD', 'Подтверждение пароля', ['required' => true, 'type' => 'password']) ?>
                <input type="submit" class="download-btn" name="change_pwd" value="Изменить пароль" />
            <? endif ?>
        </form>
    </div>
</div>
