<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props_format.php");

use App\App;
?>

<p class="title">Введите контактные данные</p>
<?
if(!empty($arResult["ORDER_PROP"]["USER_PROFILES"]))
{
	if ($arParams["ALLOW_NEW_PROFILE"] == "Y")
	{
	?>
		<div class="text-block"><?=GetMessage("SOA_TEMPL_PROP_CHOOSE")?></div>
		<select name="PROFILE_ID" id="ID_PROFILE_ID" onChange="SetContact(this.value)">
			<option value="0"><?=GetMessage("SOA_TEMPL_PROP_NEW_PROFILE")?></option>
			<?
			foreach($arResult["ORDER_PROP"]["USER_PROFILES"] as $arUserProfiles)
			{
				?>
				<option value="<?= $arUserProfiles["ID"] ?>"<?if ($arUserProfiles["CHECKED"]=="Y") echo " selected";?>><?=$arUserProfiles["NAME"]?></option>
				<?
			}
			?>
		</select>
	<?
	}
}
?>
<div style="display:none;"> <? // see `old_version` template ?>
<?
	$APPLICATION->IncludeComponent(
		"bitrix:sale.ajax.locations",
		$arParams["TEMPLATE_LOCATION"],
		array(
			"AJAX_CALL" => "N",
			"COUNTRY_INPUT_NAME" => "COUNTRY_tmp",
			"REGION_INPUT_NAME" => "REGION_tmp",
			"CITY_INPUT_NAME" => "tmp",
			"CITY_OUT_LOCATION" => "Y",
			"LOCATION_VALUE" => "",
			"ONCITYCHANGE" => "submitForm()",
		),
		null,
		array('HIDE_ICONS' => 'Y')
	);
?>
</div>

<?
PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_N"], $arParams["TEMPLATE_LOCATION"]);
PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_Y"], $arParams["TEMPLATE_LOCATION"]);
?>
