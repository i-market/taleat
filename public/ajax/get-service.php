<?
$_POST["id"] = strip_tags($_POST["id"]);
if (!$_POST["id"]) exit;
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");

$res = CIBlockElement::GetList(Array("ID","NAME","PREVIEW_TEXT"), Array("IBLOCK_ID"=>7, "ACTIVE"=>"Y", "PROPERTY_CITY"=>$_POST["id"], "PROPERTY_BRANDS"=>$_POST["brand"]));
while($ob = $res->GetNext()) echo $ob["~PREVIEW_TEXT"]."<hr>";