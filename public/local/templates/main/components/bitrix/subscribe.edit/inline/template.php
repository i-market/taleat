<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Core\Util;
?>
<div class="newsletter-sub">
    <? if ($arResult['STATE'] === 'awaiting_confirmation'): ?>
        <form class="confirmation form" action="<?=$arResult["FORM_ACTION"]?>" method="post">
            <? foreach ($arResult['ERROR'] as $msg): ?>
                <div class="form__message form__message--error">
                    <?= $msg ?>
                </div>
            <? endforeach ?>
            <div class="form__title">Подтверждение подписки</div>
            <input type="hidden" name="ID" value="<?echo $arResult["ID"];?>" />
            <input type="text" name="CONFIRM_CODE" value="<?echo $arResult["REQUEST"]["CONFIRM_CODE"];?>" placeholder="Код подтверждения" />
            <input type="submit" class="submit" name="confirm" value="Подтвердить" />
        </form>
    <? else: ?>
        <form action="<?=$arResult["FORM_ACTION"]?>" method="post">
            <?echo bitrix_sessid_post();?>
            <div class="wrap-checkbox">
                <? $id = 'checkbox-'.Util::uniqueId() ?>
                <? if ($arResult['STATE'] === 'no_subscription'): ?>
                    <input type="hidden" name="PostAction" value="Add" />
                    <input type="hidden" name="ID" value="<?echo $arResult["SUBSCRIPTION"]["ID"];?>" />
                    <input type="hidden" name="EMAIL" value="<?= $USER->GetEmail() ?>">
                    <? // default sub format ?>
                    <input type="hidden" name="FORMAT" value="text">
                    <input name="RUB_ID[]"
                           value="<?= $arResult['RUBRIC']['ID'] ?>"
                           type="checkbox"
                           hidden="hidden"
                           class="toggle"
                           id="<?= $id ?>">
                <? elseif ($arResult['STATE'] === 'subscribed'): ?>
                    <input type="hidden" name="action" value="unsubscribe" />
                    <input type="hidden" name="ID" value="<?echo $arResult["SUBSCRIPTION"]["ID"];?>" />
                    <input type="checkbox"
                           hidden="hidden"
                           checked
                           class="toggle"
                           id="<?= $id ?>">
                <? elseif ($arResult['STATE'] === 'did_unsubscribe'): ?>
                    <input type="hidden" name="action" value="activate" />
                    <input type="hidden" name="ID" value="<?echo $arResult["SUBSCRIPTION"]["ID"];?>" />
                    <input type="checkbox"
                           hidden="hidden"
                           class="toggle"
                           id="<?= $id ?>">
                <? endif ?>
                <label for="<?= $id ?>">Подписка на новости</label>
            </div>
        </form>
    <? endif ?>
</div>
