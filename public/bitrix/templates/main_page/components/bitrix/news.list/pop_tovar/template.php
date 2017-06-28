<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<h2 class="violet"><a href="/catalog/?prop=Популярные&block=none">Популярные товары</a></h2>
 <ul id="mycarousel2" class="jcarousel-skin-tango catalog pop">
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<?$href=fn_get_chainpath($arItem['IBLOCK_ID'],$arItem['IBLOCK_SECTION_ID']).$arItem["CODE"].".html";

	?>
	       <li>
              <div class="show"><div><a href="<?=$href?>">
			  
			 	  <?$rsFile = CFile::GetByID($arItem["PROPERTIES"]["OPT_DETAIL_PICTURE"]["VALUE"][4]);
				$arFile = $rsFile->Fetch();
				
		?>
			
			  <?if($arFile["HEIGHT"]<131):?>
			  <?=CFile::ShowImage($arItem["PROPERTIES"]["OPT_DETAIL_PICTURE"]["VALUE"][4])?>
			  <?else:?>
			  <?=CFile::ShowImage($arItem["PROPERTIES"]["OPT_DETAIL_PICTURE"]["VALUE"][3])?>
			  <?endif?>
			  </a></div></div>

              <div class="downLi violdark">
                <div class="title"><a href="<?=$href?>"><?=$arItem["NAME"]?></a></div>
              </div><!-- /.downLi -->
            </li>
<?endforeach;?>

</ul>