<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="otzivi-list">

<a href="/otzivi/?ADD=Y" class="add_otz" >Добавить отзыв</a>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<div class="otz_item">
		<div class="news-date-time"><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></div>
		<div class="otz_text">
			<?=$arItem["PREVIEW_TEXT"]?>
		</div>
		<div class="company">
				<?=$arItem["NAME"]?><?if ($arItem['PROPERTIES']['CITY']['VALUE']):?>, Город: <?=$arItem['PROPERTIES']['CITY']['VALUE'];?><?endif;?><?if ($arItem['PROPERTIES']['ORDER_NUM']['VALUE']):?>, Номер заказа: <?=$arItem['PROPERTIES']['ORDER_NUM']['VALUE'];?><?endif;?>
		</div>
		<?if ($arItem["DETAIL_TEXT"] != NULL):?>
			<div class="admin_text">
				<?=$arItem["DETAIL_TEXT"]?><br><b>Ответ администратора.</b>
			</div>
		<?endif;?>
	</div>
	<hr>
<?endforeach;?>
 
</div>
      <div class="pages">
			  <?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?> 


 </div>
