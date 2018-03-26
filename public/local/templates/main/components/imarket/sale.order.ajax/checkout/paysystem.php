<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\App;
use App\View as v;
?>
<p class="title">Выберите способ оплаты</p>
<?
if ($arResult["PAY_FROM_ACCOUNT"]=="Y")
{
    App::getInstance()->assert(false, 'unexpected code path');
}
?>
<div class="wrap-btn">
    <?
    foreach($arResult["PAY_SYSTEM"] as $arPaySystem)
    {
        ?>
        <label class="simple-btn" for="ID_PAY_SYSTEM_ID_<?= $arPaySystem["ID"] ?>">
            <input type="radio" id="ID_PAY_SYSTEM_ID_<?= $arPaySystem["ID"] ?>" name="PAY_SYSTEM_ID" value="<?= $arPaySystem["ID"] ?>"<?if ($arPaySystem["CHECKED"]=="Y") echo " checked=\"checked\"";?> onclick="submitForm();" />
            <?= $arPaySystem["PSA_NAME"] ?>
        </label>
        <?
    }
    ?>
</div>
<? foreach ($arResult['PAY_SYSTEM'] as $paySystem): ?>
    <? if ($paySystem['CHECKED'] === 'Y' && !v::isEmpty($paySystem['DESCRIPTION'])): ?>
        <div class="editable-area allert"><?= $paySystem['DESCRIPTION'] ?></div>
    <? endif ?>
<? endforeach ?>
