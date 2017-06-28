<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(count($arResult["SECTIONS"])>0):?>

<ul class="catalog pop cat2">
<?
foreach($arResult["SECTIONS"] as $arSection):
	$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
	$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')));
	
?>
<?
$href=fn_get_chainpath($arSection["IBLOCK_ID"], $arSection["ID"]);
?>
	
	       <li>
              <div class="topLi">
                <div class="title"><div class="tcell"><a href="<?=$href?>"><?=$arSection["NAME"]?></a></div></div>
              </div><!-- /.topLi -->

              <div class="show"><div><a href="<?=$href?>"><?=CFile::ShowImage($arSection["PICTURE"])?></a></div></div>

              <div class="downLi">
                <div class="title"><a href="<?=$href?>">Смотреть каталог</a></div>
              </div><!-- /.downLi -->
            </li>

	
	<?endforeach?>

          </ul>
		  
		  <div class="clear"></div>
		  
		  <?endif?>
		  
		  