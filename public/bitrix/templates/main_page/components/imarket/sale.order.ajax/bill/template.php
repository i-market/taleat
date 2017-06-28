<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if (!empty($arResult["ORDER"]) && 
    !empty($arResult["PAY_SYSTEM"]) && 
    strlen($arResult["PAY_SYSTEM"]["ACTION_FILE"]) > 0){?>
		<?if ($arResult["ORDER"]["STATUS_ID"] == "A" || $arResult["ORDER"]["STATUS_ID"] == "P"){?>
		<div class="print-hidden">
			<div>
				<a href="/personal/order/detail/<?=$arResult["ORDER"]["ID"]?>/">Вернуться к заказу</a>
				<br>
				<br>
				<a href="#" class="print-it" target="_blank" onclick="window.print(); return false;">Распечатать</a>
			</div>
			<br>
		</div>
		<?
        include($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"]);
		}?>
	<?} else {?>
	<p><strong>Заказ уже был оплачен или у вас нет доступа к нему</strong></p>
	<?}?>