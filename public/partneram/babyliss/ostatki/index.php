<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Остатки");
?> <a href="/partneram/ostatki/babyliss/ostatki.xls" target="_blank">Скачать остатки</a>
<br>
<br>
  	<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default", array(
							"AREA_FILE_SHOW" => "file",
							"PATH" => "/include_area/babyliss.php",
							"EDIT_TEMPLATE" => "standard.php"
							),
							false
							);
			?> <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>