<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<h2 class="orange"><a href="/catalog/?prop=DISCOUNT&block=none">Товары со скидкой</a></h2>
 <ul id="mycarousel3" class="jcarousel-skin-tango catalog ind">
<?foreach($arResult["ITEMS"] as $arItem):?>

	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<?$href=fn_get_chainpath($arItem['IBLOCK_ID'],$arItem['IBLOCK_SECTION_ID']).$arItem["CODE"].".html";

	?>
	
				
			<li>
              <div class="show"><div><a href="<?=$href?>"><div class="inderim">
			  
			  <?=$arItem["PROPERTIES"]["DISCOUNT"]["VALUE"]?>%</div>
			  
			  	  <?$rsFile = CFile::GetByID($arItem["PROPERTIES"]["OPT_DETAIL_PICTURE"]["VALUE"][4]);
				$arFile = $rsFile->Fetch();
				
		?>
			
			  <?if($arFile["HEIGHT"]<131):?>
			  <?=CFile::ShowImage($arItem["PROPERTIES"]["OPT_DETAIL_PICTURE"]["VALUE"][4])?>
			  <?else:?>
			  <?=CFile::ShowImage($arItem["PROPERTIES"]["OPT_DETAIL_PICTURE"]["VALUE"][3])?>
			  <?endif?></a></div></div>

              <div class="downLi orange">
               <div class="title"><a href="<?=$href?>"><?=$arItem["NAME"]?></a></div>
              </div><!-- /.downLi -->
            </li>
<?endforeach;?>

</ul>