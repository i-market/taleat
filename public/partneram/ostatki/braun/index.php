<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

?> 
<ul id="ost_download">
<?if((in_array(12, $arGroups))||(in_array(1, $arGroups))):?><li>
<a href="/partneram/ostatki/braun/" style="text-decoration:none;">Braun (P&amp;G-красота)</a></li>
<?endif?>
<?if((in_array(10, $arGroups))||(in_array(1, $arGroups))):?><li>
<a href="/partneram/ostatki/de-longhi/">Braun (De'Longhi-кухня)</a></li>
<?endif?>
<?if((in_array(11, $arGroups))||(in_array(1, $arGroups))):?><li>
<a href="/partneram/ostatki/electrolux/">Babyliss</a></li>
<?endif?>

</ul>
<div class='clear'></div>
<br>
<a href="/partneram/ostatki/braun/ostatki.xls" target="_blank">Скачать остатки</a>
<br>
<br>
		  	<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default", array(
							"AREA_FILE_SHOW" => "file",
							"PATH" => "/include_area/braun.php",
							"EDIT_TEMPLATE" => "standard.php"
							),
							false
							);
			?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>