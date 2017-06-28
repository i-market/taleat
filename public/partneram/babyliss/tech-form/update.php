<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

/*
$IBLOCK_ID = 10;
$ELEMENT_ID = 1871;  // код элемента

$PROPERTY_CODE = "MODEL";  // код свойства
$res = CIBlockElement::GetProperty($IBLOCK_ID, $ELEMENT_ID, "sort", "asc", array("CODE"=>"ITEM_MODEL"));
if ($ob = $res->GetNext())
	$itemModel = $ob['VALUE_ENUM'];
$PROPERTY_VALUE = $itemModel;  // значение свойства

// Установим новое значение для данного свойства данного элемента
CIBlockElement::SetPropertyValuesEx($ELEMENT_ID, false, array($PROPERTY_CODE => $PROPERTY_VALUE));


$PROPERTY_CODE = "PRODUCT";  // код свойства
$res = CIBlockElement::GetProperty($IBLOCK_ID, $ELEMENT_ID, "sort", "asc", array("CODE"=>"ITEM_PRODUCTS"));
if ($ob = $res->GetNext())
	$itemProduct = $ob['VALUE_ENUM'];
$PROPERTY_VALUE = $itemProduct;  // значение свойства
CIBlockElement::SetPropertyValuesEx($ELEMENT_ID, false, array($PROPERTY_CODE => $PROPERTY_VALUE));
*/

$IBLOCK_ID = 10;
$arSelect = Array("ID", "PROPERTY_STATUS");
$arFilter = Array("IBLOCK_ID"=>$IBLOCK_ID);
$arRes = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>5000), $arSelect);
while($arOb = $arRes->GetNextElement()){
	$arFields = $arOb->GetFields();
	if ($arFields['PROPERTY_STATUS_VALUE'] == 'Подтверждено'){
		$ELEMENT_ID = $arFields['ID'];  // id элемента
		$PROPERTY_CODE = "APPROVED";  // код свойства
		$PROPERTY_VALUE = 1;  // значение свойства
		// Установим новое значение для данного свойства данного элемента
		CIBlockElement::SetPropertyValuesEx($ELEMENT_ID, false, array($PROPERTY_CODE => $PROPERTY_VALUE));
	}
	else{
		$ELEMENT_ID = $arFields['ID'];  // id элемента
		$PROPERTY_CODE = "APPROVED";  // код свойства
		$PROPERTY_VALUE = 0;  // значение свойства
		// Установим новое значение для данного свойства данного элемента
		CIBlockElement::SetPropertyValuesEx($ELEMENT_ID, false, array($PROPERTY_CODE => $PROPERTY_VALUE));		
	}
	/*$ELEMENT_ID = $arFields['ID'];  // id элемента

	$PROPERTY_CODE = "MODEL";  // код свойства
	$res = CIBlockElement::GetProperty($IBLOCK_ID, $ELEMENT_ID, "sort", "asc", array("CODE"=>"ITEM_MODEL"));
	if ($ob = $res->GetNext())
		$itemModel = $ob['VALUE_ENUM'];
	$PROPERTY_VALUE = $itemModel;  // значение свойства

	// Установим новое значение для данного свойства данного элемента
	CIBlockElement::SetPropertyValuesEx($ELEMENT_ID, false, array($PROPERTY_CODE => $PROPERTY_VALUE));


	$PROPERTY_CODE = "PRODUCT";  // код свойства
	$res = CIBlockElement::GetProperty($IBLOCK_ID, $ELEMENT_ID, "sort", "asc", array("CODE"=>"ITEM_PRODUCTS"));
	if ($ob = $res->GetNext())
		$itemProduct = $ob['VALUE_ENUM'];
	$PROPERTY_VALUE = $itemProduct;  // значение свойства
	CIBlockElement::SetPropertyValuesEx($ELEMENT_ID, false, array($PROPERTY_CODE => $PROPERTY_VALUE));*/
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>