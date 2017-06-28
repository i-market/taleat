<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<h2 class="green"><a href="">Новинки</a></h2>
<ul id="mycarousel1" class="jcarousel-skin-tango catalog">
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<?$href=fn_get_chainpath($arSection['IBLOCK_ID'],$arSection['ID']);

	?>
	
	<li>
        <div class="show">
			<div><a href="<?=$href?>"><?=CFile::ShowImage($arItem["PROPERTIES"]["OPT_DETAIL_PICTURE"]["VALUE"][3])?></a></div></div>
			<div class="downLi violdark">
                <div class="title"><a href="<?=$href?>"><?=$arItem["NAME"]?></a></div>
            </div><!-- /.downLi -->
    </li>

<?endforeach;?>

</ul>