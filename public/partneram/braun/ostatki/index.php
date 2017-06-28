<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Остатки");
?> <a href="/partneram/ostatki_V/ostatki_braun1.xls" target="_blank" >Скачать остатки</a> 
<br />
 
<br />
 		 	<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	".default",
	Array(
		"AREA_FILE_SHOW" => "file",
		"PATH" => "/include_area/braun.php",
		"EDIT_TEMPLATE" => "standard.php"
	)
);?> <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>