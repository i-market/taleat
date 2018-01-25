<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
use App\View\FormMacros as m;

$APPLICATION->SetPageProperty('layout', 'bare');

$showHiddenInputs = function () use ($arResult) {
    ?>
    <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
    <input type="hidden" name="AUTH_FORM" value="Y">
    <input type="hidden" name="TYPE" value="SEND_PWD">
    <?
};
?>
<? // TODO if arResult.USE_CAPTCHA ?>
<div class="modal-like">
    <div class="block">
        <form class="form validate" name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
            <div class="title">Восстановление доступа</div>
            <? $result = $arParams['~AUTH_RESULT'] ?>
            <? if (!v::isEmpty($result)): ?>
                <div class="form__message <?= $result['TYPE'] === 'ERROR' ? 'form__message--error' : 'form__message--success' ?>">
                    <?= $result['MESSAGE'] ?>
                </div>
            <? endif ?>
            <? $showHiddenInputs() ?>
            <? m::showInput('USER_LOGIN', 'Логин', ['value' => $arResult['LAST_LOGIN']]) ?>
            <hr class="hr-text" data-content="или">
            <? m::showInput('USER_EMAIL', 'E-mail') ?>
            <input type="submit" class="download-btn" name="send_account_info" value="Восстановить" />
        </form>
    </div>
</div>
