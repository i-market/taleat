<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$shopPassword = "3456869";
$md5 = mb_strtoupper(md5($_REQUEST["action"].";".$_REQUEST["orderSumAmount"].";".$_REQUEST["orderSumCurrencyPaycash"].";".$_REQUEST["orderSumBankPaycash"].";".$_REQUEST["shopId"].";".$_REQUEST["invoiceId"].";".$_REQUEST["customerNumber"].";".$shopPassword));


CModule::IncludeModule("iblock");
CModule::IncludeModule('sale');

$ID = intval($_REQUEST["orderId"]);
$arOrder = CSaleOrder::GetByID($ID);

function log_array() {
   $arArgs = func_get_args();
   $sResult = '';
   foreach($arArgs as $arArg) {
      $sResult .= "\n\n".print_r($arArg, true);
   }

   if(!defined('LOG_FILENAME')) {
      define('LOG_FILENAME', $_SERVER['DOCUMENT_ROOT'].'/bitrix/log.txt');
   }
   AddMessage2Log($sResult, 'log_array -> ');
}

log_array($arOrder);
log_array($_REQUEST);

$ord = array();
$ord["ID"]		= $arOrder["ID"];
$ord["STATUS"]	= $arOrder["STATUS_ID"];
$ord["PRICE"]	= $arOrder["PRICE"];


$rsUser = CUser::GetByID($arOrder["USER_ID"]);
$arUser = $rsUser->Fetch();
$ord["LOGIN"] = $arUser["LOGIN"];

$error = 0;
if (!$arOrder) $error = 1;
if ($ord["ID"] != $_REQUEST["orderId"]) $error = 1;
if ($ord["LOGIN"] != $_REQUEST["customerNumber"]) $error = 1;
if ($ord["PRICE"] != $_REQUEST["orderSumAmount"]) $error = 1;
if ($ord["STATUS"] != "A")  $error = 1;
if ($_REQUEST["md5"] != $md5)  $error = 2;

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";?><?
	switch($error){
		case 0:?><paymentAvisoResponse performedDatetime="<?=date("c")?>" code="0" invoiceId="<?=htmlspecialchars($_REQUEST["invoiceId"])?>" shopId="<?=htmlspecialchars($_REQUEST["shopId"])?>"/>
		<?break;
		case 1:?><paymentAvisoResponse performedDatetime="<?=date("c")?>" code="200" invoiceId="<?=htmlspecialchars($_REQUEST["invoiceId"])?>" shopId="<?=htmlspecialchars($_REQUEST["shopId"])?>"/>
		<?break;
		case 2:?><paymentAvisoResponse performedDatetime="<?=date("c")?>" code="1" invoiceId="<?=htmlspecialchars($_REQUEST["invoiceId"])?>" shopId="<?=htmlspecialchars($_REQUEST["shopId"])?>"/>
		<?break;
	}
	
if ($error == 0){
	$arFields = array();
	$arFields["PAYED"] = "Y";
	$arFields["STATUS_ID"] = "P";
	$arFields["DATE_PAYED"] = Date(CDatabase::DateFormatToPHP(CLang::GetDateFormat("FULL", LANG)));
	$arFields["EMP_PAYED_ID"] = false;
	CSaleOrder::Update($ord["ID"], $arFields);
 

	$arFields = array();
	$db_props = CSaleOrderPropsValue::GetOrderProps($ID);
	while ($arProps = $db_props->Fetch()){
		//print_r($arProps);
		if($arProps["CODE"] == "EMAIL")
			$arFields["EMAIL"] = $arProps["VALUE"];
		if($arProps["CODE"] == "FAM")
			$arFields["FAM"] = $arProps["VALUE"];
		if($arProps["CODE"] == "IMYA")
			$arFields["IMYA"] = $arProps["VALUE"];
		if($arProps["CODE"] == "OTCHESTVO")
			$arFields["OTCHESTVO"] = $arProps["VALUE"];
	}

	$arFields["SALE_EMAIL"] = COption::GetOptionString("sale", "order_email");
	$arFields["COMMENTS"] = $arOrder["COMMENTS"];
	$arFields["ORDER_ID"] = $ID;
	$arFields["ORDER_DATE"] = $arOrder["DATE_INSERT"];

	if (!$arFields["FAM"] && !$arFields["IMYA"] && !$arFields["OTCHESTVO"]) $arFields["FULL_NAME"] = "клиент";
	else $arFields["FULL_NAME"] = $arFields["FAM"]." ".$arFields["IMYA"]." ".$arFields["OTCHESTVO"];

	CEvent::SendImmediate("STATUS_PAY", "s1", $arFields);	
	
}
?>
