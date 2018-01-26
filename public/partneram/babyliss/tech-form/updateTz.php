<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
require_once("stamp.php");
$APPLICATION->SetTitle("Техническое заключение");

$num = "";
$scName = "";
$dateReport = "";
$scPhone = "";
$scAddress = "";
$ownerTitle = "";
$ownerPhone = "";
$ownerAddress = "";
$itemType = "";
$itemCreated = "";
$itemProduct = "";
$itemModel = "";
$itemDateSale = "";
$itemComplect = "";
$itemDateGet = "";
$itemFault = "";
$itemRepairs = "";
$itemRealFault = "";
$reportFault = "";
$userFault = "";
$reasonFailRepair = "";
$itemPlace = "";
$status = "";

$IBLOCK_ID = 10;
$ID = $_GET['ID'];
$arSelect = Array("ID", "NAME");
$arFilter = Array("IBLOCK_ID"=>10, "ID"=>$ID, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
if ($ob = $res->GetNextElement()){
	$arFields = $ob->GetFields();
}
$arName = explode(" ", $arFields['NAME']);
$num = $arName[count($arName)-1];

$res = CIBlockElement::GetProperty($IBLOCK_ID, $ID, "sort", "asc", array("CODE"=>"STATUS"));
if ($ob = $res->GetNext())
	$status = $ob['~VALUE_ENUM'];

$res = CIBlockElement::GetProperty($IBLOCK_ID, $ID, "sort", "asc", array("CODE"=>"SC_NAME"));
if ($ob = $res->GetNext())
	$scName = $ob['~VALUE'];

$res = CIBlockElement::GetProperty($IBLOCK_ID, $ID, "sort", "asc", array("CODE"=>"DATE_REPORT"));
if ($ob = $res->GetNext())
	$dateReport = $ob['~VALUE'];

$res = CIBlockElement::GetProperty($IBLOCK_ID, $ID, "sort", "asc", array("CODE"=>"SC_PHONE"));
if ($ob = $res->GetNext())
	$scPhone = $ob['~VALUE'];

$res = CIBlockElement::GetProperty($IBLOCK_ID, $ID, "sort", "asc", array("CODE"=>"SC_ADDRESS"));
if ($ob = $res->GetNext())
	$scAddress = $ob['~VALUE'];

$res = CIBlockElement::GetProperty($IBLOCK_ID, $ID, "sort", "asc", array("CODE"=>"OWNER_TITLE"));
if ($ob = $res->GetNext())
	$ownerTitle = $ob['~VALUE'];

$res = CIBlockElement::GetProperty($IBLOCK_ID, $ID, "sort", "asc", array("CODE"=>"OWNER_PHONE"));
if ($ob = $res->GetNext())
	$ownerPhone = $ob['~VALUE'];

$res = CIBlockElement::GetProperty($IBLOCK_ID, $ID, "sort", "asc", array("CODE"=>"OWNER_ADDRESS"));
if ($ob = $res->GetNext())
	$ownerAddress = $ob['~VALUE'];

$res = CIBlockElement::GetProperty($IBLOCK_ID, $ID, "sort", "asc", array("CODE"=>"ITEM_TYPE"));
if ($ob = $res->GetNext())
	$itemType = $ob['~VALUE'];

$res = CIBlockElement::GetProperty($IBLOCK_ID, $ID, "sort", "asc", array("CODE"=>"ITEM_CREATED"));
if ($ob = $res->GetNext())
	$itemCreated = $ob['~VALUE'];

$res = CIBlockElement::GetProperty($IBLOCK_ID, $ID, "sort", "asc", array("CODE"=>"PRODUCT"));
if ($ob = $res->GetNext())
	$itemProduct = $ob['~VALUE'];

$res = CIBlockElement::GetProperty($IBLOCK_ID, $ID, "sort", "asc", array("CODE"=>"MODEL"));
if ($ob = $res->GetNext())
	$itemModel = $ob['~VALUE'];

$res = CIBlockElement::GetProperty($IBLOCK_ID, $ID, "sort", "asc", array("CODE"=>"ITEM_DATE_SALE"));
if ($ob = $res->GetNext())
	$itemDateSale = $ob['~VALUE'];

$res = CIBlockElement::GetProperty($IBLOCK_ID, $ID, "sort", "asc", array("CODE"=>"ITEM_COMPLECT"));
if ($ob = $res->GetNext()){
	$itemComplect .= $ob['~VALUE_ENUM'];
}
while($ob = $res->GetNext()){
	$itemComplect .= ', '.$ob['~VALUE_ENUM'];
}

$res = CIBlockElement::GetProperty($IBLOCK_ID, $ID, "sort", "asc", array("CODE"=>"ITEM_DATE_GET"));
if ($ob = $res->GetNext())
	$itemDateGet = $ob['~VALUE'];

$res = CIBlockElement::GetProperty($IBLOCK_ID, $ID, "sort", "asc", array("CODE"=>"ITEM_FAULT"));
if ($ob = $res->GetNext())
	$itemFault = $ob['~VALUE'];

$res = CIBlockElement::GetProperty($IBLOCK_ID, $ID, "sort", "asc", array("CODE"=>"ITEM_REPAIRS"));
if ($ob = $res->GetNext())
	$itemRepairs = $ob['~VALUE'];

$res = CIBlockElement::GetProperty($IBLOCK_ID, $ID, "sort", "asc", array("CODE"=>"ITEM_REAL_FAULT"));
if ($ob = $res->GetNext())
	$itemRealFault = $ob['~VALUE'];

$res = CIBlockElement::GetProperty($IBLOCK_ID, $ID, "sort", "asc", array("CODE"=>"REPORT_FAULT"));
if ($ob = $res->GetNext())
	$reportFault = $ob['~VALUE_ENUM'];

$res = CIBlockElement::GetProperty($IBLOCK_ID, $ID, "sort", "asc", array("CODE"=>"USER_FAULT"));
if ($ob = $res->GetNext())
	$userFault = $ob['~VALUE'];

$res = CIBlockElement::GetProperty($IBLOCK_ID, $ID, "sort", "asc", array("CODE"=>"REASON_FAIL_REPAIR"));
if ($ob = $res->GetNext())
	$reasonFailRepair = $ob['~VALUE_ENUM'];

$res = CIBlockElement::GetProperty($IBLOCK_ID, $ID, "sort", "asc", array("CODE"=>"ITEM_PLACE"));
if ($ob = $res->GetNext())
	$itemPlace = $ob['~VALUE_ENUM'];

require_once $_SERVER["DOCUMENT_ROOT"].'/bitrix/templates/main_page/include/phpexcel/PHPExcel/IOFactory.php';
$book = PHPExcel_IOFactory::load($_SERVER["DOCUMENT_ROOT"]."/partneram/babyliss/tech-form/zakl_new1.xls");

$book->getActiveSheet()->setCellValue('A1', "Техническое заключение на изделие BABYLISS №".$num);
$book->getActiveSheet()->setCellValue('A4', $scName);
$book->getActiveSheet()->setCellValue('F3', $dateReport);
$book->getActiveSheet()->setCellValue('A8', $scAddress);
$book->getActiveSheet()->setCellValue('D7', $scPhone);

$book->getActiveSheet()->setCellValue('B10', $ownerTitle);
$book->getActiveSheet()->setCellValue('H10', $ownerPhone);
$book->getActiveSheet()->setCellValue('A11', $ownerAddress);

$book->getActiveSheet()->setCellValue('C13', $itemProduct);
$book->getActiveSheet()->setCellValue('C14', $itemModel);
$book->getActiveSheet()->setCellValue('H12', $itemType);
$book->getActiveSheet()->setCellValue('I14', $itemCreated);
$book->getActiveSheet()->setCellValue('C15', $itemDateSale);

$book->getActiveSheet()->setCellValue('C16', $itemComplect);
$book->getActiveSheet()->setCellValue('E17', $itemDateGet);
$book->getActiveSheet()->setCellValue('A19', $itemFault);
$book->getActiveSheet()->setCellValue('E20', $itemRepairs);

$book->getActiveSheet()->setCellValue('D23', $itemRealFault);
$book->getActiveSheet()->setCellValue('F24', $reportFault);
if ($reportFault == "Нарушение правил эксплуатации")
	$book->getActiveSheet()->setCellValue('A25', $userFault);

$book->getActiveSheet()->setCellValue('A34', $reasonFailRepair);
$book->getActiveSheet()->setCellValue('A36', $itemPlace);

if ($status == "Подтверждено"){
	$ELEMENT_ID = $_GET['ID'];  // id элемента

	$PROPERTY_CODE = "APPROVED";  // код свойства
	$PROPERTY_VALUE = "1";  // значение свойства
	CIBlockElement::SetPropertyValuesEx($ELEMENT_ID, false, array($PROPERTY_CODE => $PROPERTY_VALUE));
	
	$objDrawing = new \PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Sample image');
	$objDrawing->setDescription('Sample image');
	$objDrawing->setPath($_SERVER["DOCUMENT_ROOT"]."/partneram/babyliss/tech-form/stamp.jpg");
	$objDrawing->setHeight(45);
	$objDrawing->setCoordinates('I2');
	$objDrawing->setWorksheet($book->getActiveSheet());

	$arItem = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>$IBLOCK_ID, "ID"=>$ID), false, false, Array("ID", "PROPERTY_STATUS", "PROPERTY_USER", "PROPERTY_NUMER"))->GetNext();
	$arUser = CUser::GetList(($by = "sort"), ($order = "asc"), Array("ID"=>$arItem["PROPERTY_USER_VALUE"]), Array("SELECT"=>Array("EMAIL")))->GetNext();
	$arMailFields = Array(
		"EMAIL" => $arUser["EMAIL"],
		"STATUS" => $status,
		"NUMBER" => $arItem["PROPERTY_NUMER_VALUE"] 
	);
	CEvent::Send("CHANGE_STATUS_TZ", "s1", $arMailFields);
}
else{
	$ELEMENT_ID = $_GET['ID'];  // id элемента

	$PROPERTY_CODE = "APPROVED";  // код свойства	
	$PROPERTY_VALUE = "0";  // значение свойства
	CIBlockElement::SetPropertyValuesEx($ELEMENT_ID, false, array($PROPERTY_CODE => $PROPERTY_VALUE));	
}
$objWriter = PHPExcel_IOFactory::createWriter($book, 'Excel5');
$arNum = explode('/', $num);
$fileName = 'new_tz_'.$arNum[0].'_'.$arNum[1].'.xls';
// TODO use a temporary directory
$objWriter->save($_SERVER["DOCUMENT_ROOT"]."/partneram/babyliss/tech-form/".$fileName);
CIBlockElement::SetPropertyValuesEx($ID, IB_REPORTS, Array("FORMA"=>CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/partneram/babyliss/tech-form/".$fileName)));
LocalRedirect('/bitrix/admin/iblock_list_admin.php?IBLOCK_ID=10&type=region&lang=ru&find_section_section=0');
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>