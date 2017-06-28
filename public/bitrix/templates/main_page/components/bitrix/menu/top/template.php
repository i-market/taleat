<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>
<div class="bar">
<ul>

<?if ($APPLICATION->GetCurPage() == "/catalog/") $arResult[0]["SELECTED"] = "";
foreach($arResult as $pid=>$arItem):
if($pid>0):?><li class="sep"></li><?endif?>
<li <?if($arItem["SELECTED"]):?>class="active"<?endif?>>
    <a<?if($arItem["PARAMS"]["class"]):?> class="<?=$arItem["PARAMS"]["class"]?>"<?endif?> href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
</li>
<?endforeach?>

</ul>
</div>
<?endif?>