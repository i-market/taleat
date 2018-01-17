<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$APPLICATION->SetPageProperty('layout', 'default');
?>
<p>
    <a href="<?=$arResult["URL_TO_LIST"]?>"><?=Loc::getMessage("SALE_RECORDS_LIST")?></a>
</p>
<div class="bx_my_order_cancel">
    <?if(strlen($arResult["ERROR_MESSAGE"])<=0):?>
        <form method="post" action="<?=POST_FORM_ACTION_URI?>">

            <input type="hidden" name="CANCEL" value="Y">
            <?=bitrix_sessid_post()?>
            <input type="hidden" name="ID" value="<?=$arResult["ID"]?>">

            <p>
                <?=Loc::getMessage("SALE_CANCEL_ORDER1") ?>
                <a href="<?=$arResult["URL_TO_DETAIL"]?>"><?=Loc::getMessage("SALE_CANCEL_ORDER2")?> #<?=$arResult["ACCOUNT_NUMBER"]?></a>?
                <b><?= Loc::getMessage("SALE_CANCEL_ORDER3") ?></b><br /><br />
                <?= Loc::getMessage("SALE_CANCEL_ORDER4") ?>:<br />
            </p>

            <textarea name="REASON_CANCELED"></textarea><br /><br />
            <input class="btn" type="submit" name="action" value="<?=Loc::getMessage("SALE_CANCEL_ORDER_BTN") ?>">

        </form>
    <?else:?>
        <?=ShowError($arResult["ERROR_MESSAGE"]);?>
    <?endif;?>

</div>
