<?//$_SERVER["DOCUMENT_ROOT"] = "/home/i/imarket98/public_html"; 
//$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"]; 

define("NO_KEEP_STATISTIC", true); 
define("NOT_CHECK_PERMISSIONS", true); 

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 
set_time_limit(0); 

global $APPLICATION;
CModule::IncludeModule("iblock");

$datetime2 = date_create(date("Y-m-d"));
$arSelect = Array("NAME", "ID", "ACTIVE_FROM");
$arFilter = Array("IBLOCK_ID"=>10);
$res = CIBlockElement::GetList(Array("ACTIVE_FROM"=>"ASC"), $arFilter, false, false, $arSelect);
$i = 0;

if($ob = $res->Fetch()){
	$datetime1 = date_create(date("Y-m-d",MakeTimeStamp($ob["ACTIVE_FROM"], "DD.MM.YYYY")));
	$interval  = date_diff($datetime1, $datetime2);

	$years = intval($interval->format('%y'));
	if ($years>0) CIBlockElement::Delete($ob["ID"]);
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php"); 