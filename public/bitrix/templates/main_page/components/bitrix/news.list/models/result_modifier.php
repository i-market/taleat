<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<?$arModels = CIBlockElement::GetProperty(11, $arResult['ITEMS'][0]['ID'], "sort", "asc", array("CODE"=>"MODELS"));?>
<?$arRes = Array();?>
<?while($arModel = $arModels->GetNext()):?>
	<?$arRes[$arModel['VALUE']] = $arModel['VALUE_ENUM'];?>
<?endwhile;?>
<?asort($arRes);?>
<?$arResult['MODELS'] = $arRes;?>