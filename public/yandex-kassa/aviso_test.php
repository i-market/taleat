<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$shopPassword = "ytZQtFxdAb";
$md5 = mb_strtoupper(md5($_REQUEST["action"].";".$_REQUEST["orderSumAmount"].";".$_REQUEST["orderSumCurrencyPaycash"].";".$_REQUEST["orderSumBankPaycash"].";".$_REQUEST["shopId"].";".$_REQUEST["invoiceId"].";".$_REQUEST["customerNumber"].";".$shopPassword));
CModule::IncludeModule('iblock');
$arItem = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>10, "ID"=>$_REQUEST["orderId"], "ACTIVE"=>"N"), false, false, Array("ID", "PROPERTY_DURATION", "PROPERTY_USER"))->GetNext();
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
if($_REQUEST["md5"] != $md5):?>
    <paymentAvisoResponse performedDatetime="<?=date("c")?>" code="1" invoiceId="<?=htmlspecialchars($_REQUEST["invoiceId"])?>" shopId="<?=htmlspecialchars($_REQUEST["shopId"])?>"/>
<?elseif($arItem && $_REQUEST["orderId"]):
    $month = 0;
    $arUser = CUser::GetList(($by="id"), ($order="asc"), Array("ID"=>$arItem["PROPERTY_USER_VALUE"]), Array("FIELDS"=>Array("EMAIL", "NAME")))->GetNext();
    $arMailFields = Array(
        "EMAIL"=>$arUser["EMAIL"],
        "NAME"=>$arUser["NAME"]
    );
    if($arItem["PROPERTY_DURATION_ENUM_ID"] == 2):
        $month = 1;
        $arMailFields["DURATION"] = "1 месяц";
    endif;
    
    if($arItem["PROPERTY_DURATION_ENUM_ID"] == 3):
        $month = 3;
        $arMailFields["DURATION"] = "3 месяца";
    endif;
    $arElement = new CIBlockElement;
    $arFields = Array(
        "ACTIVE_FROM"=>date("d.m.Y"),
        "ACTIVE_TO"=>date("d.m.Y",mktime(0,0,0,date("m")+$month,date("d"),date("Y"))),
        "ACTIVE"=>"Y"
    );
    $arElement->Update($_REQUEST["orderId"], $arFields);
    CEvent::Send("PAYMENT_START", "s1", $arMailFields);
    ?>
    <paymentAvisoResponse performedDatetime="<?=date("c")?>" code="0" invoiceId="<?=htmlspecialchars($_REQUEST["invoiceId"])?>" shopId="<?=htmlspecialchars($_REQUEST["shopId"])?>"/>
<?else:?>
    <paymentAvisoResponse performedDatetime="<?=date("c")?>" code="200" invoiceId="<?=htmlspecialchars($_REQUEST["invoiceId"])?>" shopId="<?=htmlspecialchars($_REQUEST["shopId"])?>"/>
<?endif?>