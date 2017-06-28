<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Остатки");
?> 
<a href="/partneram/ostatki/de-longhi/ostatki.xls" target="_blank">Скачать остатки</a>
<br>
<br>
  	<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default", array(
							"AREA_FILE_SHOW" => "file",
							"PATH" => "/include_area/de-longhi.php",
							"EDIT_TEMPLATE" => "standard.php"
							),
							false
							);
			?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>