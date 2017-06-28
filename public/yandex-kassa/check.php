<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$shopPassword = "3456869";
$md5 = mb_strtoupper(md5($_REQUEST["action"].";".$_REQUEST["orderSumAmount"].";".$_REQUEST["orderSumCurrencyPaycash"].";".$_REQUEST["orderSumBankPaycash"].";".$_REQUEST["shopId"].";".$_REQUEST["invoiceId"].";".$_REQUEST["customerNumber"].";".$shopPassword));


CModule::IncludeModule("iblock");
CModule::IncludeModule('sale');

$ID = intval($_REQUEST["orderId"]);
$arOrder = CSaleOrder::GetByID($ID);

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
		case 0:?><checkOrderResponse performedDatetime="<?=date("c")?>" code="0" invoiceId="<?=$_REQUEST["invoiceId"]?>" shopId="<?=$_REQUEST["shopId"]?>"/>
		<?break;
		case 1:?><checkOrderResponse performedDatetime="<?=date("c")?>" code="100" invoiceId="<?=$_REQUEST["invoiceId"]?>" shopId="<?=$_REQUEST["shopId"]?>" message="Заказ не найден или уже оплачен" />		
		<?break;
		case 2:?><checkOrderResponse performedDatetime="<?=date("c")?>" code="1" invoiceId="<?=$_REQUEST["invoiceId"]?>" shopId="<?=$_REQUEST["shopId"]?>"/>
		<?break;
	}
?>
