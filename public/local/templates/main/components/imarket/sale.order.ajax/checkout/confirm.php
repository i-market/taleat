<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$APPLICATION->SetPageProperty('body_class', '');
?>
<div class="editable-area default-page">
    <? if (empty($arResult["ORDER"])): ?>
        <h2 class="h3"><?= GetMessage("SOA_TEMPL_ERROR_ORDER") ?></h2>
        <p><?= GetMessage("SOA_TEMPL_ERROR_ORDER_LOST", Array("#ORDER_ID#" => $arResult["ORDER_ID"])) ?></p>
        <p><?= GetMessage("SOA_TEMPL_ERROR_ORDER_LOST1") ?></p>
    <? else: ?>
        <h2 class="h3"><?= GetMessage("SOA_TEMPL_ORDER_COMPLETE") ?></h2>
        <p><?= GetMessage("SOA_TEMPL_ORDER_SUC", Array("#ORDER_DATE#" => $arResult["ORDER"]["DATE_INSERT"], "#ORDER_ID#" => $arResult["ORDER"]["ACCOUNT_NUMBER"])) ?></p>
        <p><?= GetMessage("SOA_TEMPL_ORDER_SUC1", Array("#LINK#" => $arParams["PATH_TO_PERSONAL"])) ?></p>
        <? if (!empty($arResult["PAY_SYSTEM"])): ?>
            <p><?= GetMessage("SOA_TEMPL_PAY") ?>: <?= $arResult["PAY_SYSTEM"]["NAME"] ?></p>
            <? if (strlen($arResult["PAY_SYSTEM"]["ACTION_FILE"]) > 0): ?>
                <? if ($arResult["PAY_SYSTEM"]["NEW_WINDOW"] == "Y"): ?>
                    <script language="JavaScript">
                      window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))?>');
                    </script>
                    <?= GetMessage("SOA_TEMPL_PAY_LINK", Array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))))?>
                    <? if (CSalePdf::isPdfAvailable()): ?>
                        <?= GetMessage("SOA_TEMPL_PAY_PDF", Array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))."&pdf=1&DOWNLOAD=Y")) ?>
                    <? endif ?>
                <? elseif (strlen($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"])>0): ?>
                    </div> <? // break out of the wrapper div ?>
                    <? include($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"]); ?>
                    <div>
                <? endif ?>
            <? endif ?>
        <? endif ?>
    <? endif ?>
</div>
