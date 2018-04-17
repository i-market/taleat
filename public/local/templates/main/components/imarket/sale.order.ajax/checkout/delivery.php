<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use App\App;
use App\View as v;
use App\Order;

$deliveryServices = Order::filterDeliveryServices($arResult['DELIVERY'], $arResult['USER_VALS']['DELIVERY_LOCATION']);
?>
<?
if(!empty($deliveryServices))
{
	?>
    <p class="title">Выберите вариант доставки заказа</p>
    <div class="wrap-btn">
		<?
		foreach ($deliveryServices as $delivery_id => $arDelivery)
		{
			if ($delivery_id !== 0 && intval($delivery_id) <= 0)
            {
                App::getInstance()->assert(false, 'unexpected code path');
                ?>
                <div>
                    <b><?=htmlspecialcharsbx($arDelivery["TITLE"])?></b><?if (strlen($arDelivery["DESCRIPTION"]) > 0):?><br />
                        <?=nl2br($arDelivery["DESCRIPTION"])?><br /><?endif;?>
                    <table border="0" cellspacing="0" cellpadding="3">
                        <?
                        foreach ($arDelivery["PROFILES"] as $profile_id => $arProfile)
                        {
                            ?>
                            <tr>
                                <td width="0%" valign="top">
                                    <input type="radio" id="ID_DELIVERY_<?=$delivery_id?>_<?=$profile_id?>" name="<?=htmlspecialcharsbx($arProfile["FIELD_NAME"])?>" value="<?=$delivery_id.":".$profile_id;?>" <?=$arProfile["CHECKED"] == "Y" ? "checked=\"checked\"" : "";?> onclick="submitForm();" />
                                </td>
                                <td width="50%" valign="top">
                                    <label for="ID_DELIVERY_<?=$delivery_id?>_<?=$profile_id?>">
                                        <small><b><?=htmlspecialcharsbx($arProfile["TITLE"])?></b><?if (strlen($arProfile["DESCRIPTION"]) > 0):?><br />
                                                <?=nl2br($arProfile["DESCRIPTION"])?><?endif;?></small>
                                    </label>
                                </td>
                                <td width="50%" valign="top" align="right">
                                    <?
                                    $APPLICATION->IncludeComponent('bitrix:sale.ajax.delivery.calculator', '', array(
                                        "NO_AJAX" => $arParams["DELIVERY_NO_AJAX"],
                                        "DELIVERY" => $delivery_id,
                                        "PROFILE" => $profile_id,
                                        "ORDER_WEIGHT" => $arResult["ORDER_WEIGHT"],
                                        "ORDER_PRICE" => $arResult["ORDER_PRICE"],
                                        "LOCATION_TO" => $arResult["USER_VALS"]["DELIVERY_LOCATION"],
                                        "LOCATION_ZIP" => $arResult["USER_VALS"]["DELIVERY_LOCATION_ZIP"],
                                        "CURRENCY" => $arResult["BASE_LANG_CURRENCY"],
                                    ), null, array('HIDE_ICONS' => 'Y'));
                                    ?>

                                </td>
                            </tr>
                            <?
                        } // endforeach
                        ?>
                    </table>
                </div>
                <?
            }
            else
			{
				?>
                <label class="simple-btn" for="ID_DELIVERY_ID_<?= $arDelivery["ID"] ?>">
                    <input type="radio" id="ID_DELIVERY_ID_<?= $arDelivery["ID"] ?>" name="<?=htmlspecialcharsbx($arDelivery["FIELD_NAME"])?>" value="<?= $arDelivery["ID"] ?>"<?if ($arDelivery["CHECKED"]=="Y") echo " checked";?> onclick="submitForm();" />
                    <?= htmlspecialcharsbx($arDelivery["NAME"]) ?>
                </label>
				<?
			}
		}
		?>
    </div>
    <? foreach ($deliveryServices as $delivery): ?>
        <? if ($delivery['CHECKED'] === 'Y' && !v::isEmpty($delivery['DESCRIPTION'])): ?>
            <div class="editable-area allert"><?= htmlspecialcharsback($delivery['DESCRIPTION']) ?></div>
        <? endif ?>
    <? endforeach ?>
    <?
}
?>