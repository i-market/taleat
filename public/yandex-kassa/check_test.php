<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$shopPassword = "ytZQtFxdAb";
$md5 = mb_strtoupper(md5($_REQUEST["action"].";".$_REQUEST["orderSumAmount"].";".$_REQUEST["orderSumCurrencyPaycash"].";".$_REQUEST["orderSumBankPaycash"].";".$_REQUEST["shopId"].";".$_REQUEST["invoiceId"].";".$_REQUEST["customerNumber"].";".$shopPassword));
CModule::IncludeModule('iblock');
$arItem = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>10, "ID"=>$_REQUEST["orderId"], "ACTIVE"=>"N"), false, false, Array("ID"))->GetNext();
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
if($_REQUEST["md5"] != $md5):?>
    <checkOrderResponse performedDatetime="<?=date("c")?>" code="1" invoiceId="<?=$_REQUEST["invoiceId"]?>" shopId="<?=$_REQUEST["shopId"]?>"/>
<?elseif($arItem && $_REQUEST["orderId"]):?>
    <checkOrderResponse performedDatetime="<?=date("c")?>" code="0" invoiceId="<?=$_REQUEST["invoiceId"]?>" shopId="<?=$_REQUEST["shopId"]?>"/>
<?else:?>
    <checkOrderResponse performedDatetime="<?=date("c")?>" code="100" invoiceId="<?=$_REQUEST["invoiceId"]?>" shopId="<?=$_REQUEST["shopId"]?>" message="Заказ не найден или уже оплачен" />
<?endif?>