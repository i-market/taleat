<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
require_once("stamp.php");
$APPLICATION->SetTitle("Техническое заключение");

$IBLOCK_ID = 10;
$ID = 2509;
$arSelect = Array("ID", "NAME");
$arFilter = Array("IBLOCK_ID"=>10, "ID"=>$ID, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");

$res = CIBlockElement::GetProperty($IBLOCK_ID, $ID, "sort", "asc", array("CODE"=>"OWNER_TITLE"));
if ($ob = $res->GetNext())
	$ownerTitle = $ob['VALUE'];
?>
<pre><?print_r(strip_tags($ownerTitle));?></pre>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>