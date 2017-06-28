<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$dep=0;?>
<ul id="treeview" class="sidebar treeview">
<?
foreach($arResult["SECTIONS"] as $arSection):
	$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
	$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')));
?>

<?
$href=fn_get_chainpath($arSection['IBLOCK_ID'],$arSection['ID']);
if($dep==1){
if($arSection["DEPTH_LEVEL"]==1)
	{
		$dep=0;
		?>
		   </ul>
              </li>
		<?
	}
	}
?>

<?if($arSection["DEPTH_LEVEL"]==1){

 $arFilter = Array('IBLOCK_ID'=>$arSection["IBLOCK_ID"], "SECTION_ID"=>$arSection["ID"], "ACTIVE"=>"Y");
 $db_list = CIBlockSection::GetList(Array($by=>$order), $arFilter, true);
 $count=$db_list->SelectedRowsCount();

if($count>0)
{$dep=1;
$p=0;
?>
 <li class="expandable">
             <div class="hitarea expandable-hitarea <?if(preg_match("#".$href."#", $APPLICATION->GetCurDir())):?><?$p=1;?>opr<?endif?>">
				<a href="<?=$href?>" class=""><?=$arSection["NAME"]?></a></div>
				 <ul <?if($p==0):?>style="display:none;"<?endif?>>
				 
<?}
else{
?>          <li><div><a href="<?=$href?>"><?=$arSection["NAME"]?></a></div></li><?

}
?>

 <?}
	else{
		?>
		 <li><a href="<?=$href?>"><?=$arSection["NAME"]?></a></li>
		
		<?
	
	}
 
 ?>
	

	
<?endforeach?>
</ul>
</li>
</ul>