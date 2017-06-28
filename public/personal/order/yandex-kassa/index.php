<?
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оплата заказа через Яндекс.кассу");

$error = "";
$ID = intval($_REQUEST["ORDER_ID"]);
if ($ID){
	CModule::IncludeModule('sale');
	$arOrder = CSaleOrder::GetByID($ID);
	if ($USER->GetID()!==$arOrder["USER_ID"]) $error = "Заказ был оформлен на другого пользователя";
	if ($arOrder["STATUS_ID"]!="A") $error = "Заказ ещё не был проверен менеджером или уже оплачен";
	if ($arOrder["PAY_SYSTEM_ID"]!="8") $error = "Оплата заказа по другой платёжной системе";	

	$rsUser = CUser::GetByID($USER->GetID());
	$arUser = $rsUser->Fetch();
	$db_props = CSaleOrderPropsValue::GetOrderProps($ID);
	while ($arProps = $db_props->Fetch()){
		if ($arProps["PROPERTY_NAME"] == "E-Mail") $arUser["EMAIL"] = $arProps["VALUE"];
	}
	
} else $error = "Ваш заказ не найден";?>
<style>
.payment-method label {
    display: block;
    padding-bottom: 15px;
    font-size: 15px;
}

.payment-method label:hover{
	cursor:pointer;
}

.payment-method label div{
    vertical-align: middle;
    display: inline-block;
    position: relative;
    top: -17px;
}

.payment-method strong{
	font-size: 15px;
	
}
</style>
<?if (!$error && $arOrder){?>
<div style="font-size: 15px; margin-bottom: 25px;">
	Ваш заказ <strong>№<?=$arOrder["ID"]?></strong> от <?=mb_strtolower(FormatDate("j F Y г.", MakeTimeStamp($arOrder["DATE_INSERT"], "YYYY-MM-DD HH:MI:SS")))?> на сумму <strong><?=CurrencyFormat($arOrder["PRICE"], "RUB");?></strong>
	<div style="margin-top:10px;">Подробная информация о заказе: <a href="/personal/order/detail/<?=$arOrder["ID"]?>/" target="_blank">№<?=$arOrder["ID"]?></a></div>
</div>
<form action="https://money.yandex.ru/eshop.xml" method="post">
	<div class="payment-method">
		<div class="center"><strong>Выберите способ оплаты:</strong></div>
		<br />                        
		<label>
			<img src="/personal/order/yandex-kassa/dengi.png" alt="" />
			<div><input type="radio" name="paymentType" value="PC" checked="checked" /> Со счета в Яндекс.Деньгах</div>
		</label>
		<label>
			<img src="/personal/order/yandex-kassa/visa.png" alt="" />
			<div><input type="radio" name="paymentType" value="AC"/> С банковской карты</div>
		</label>
	</div>
	
	<input name="shopId" value="49286" type="hidden"/> 
	<input name="scid" value="33548" type="hidden"/>
	<input name="sum" value="<?=$arOrder["PRICE"]?>" type="hidden">
	<input name="customerNumber" value="<?=$arUser["LOGIN"]?>" type="hidden"/>
	<input name="orderId" value="<?=$arOrder["ID"]?>" type="hidden"/>
	<input name="cps_email" value="<?=$arUser["EMAIL"]?>" type="hidden"/> 
	<input type="submit" style="text-transform: uppercase; color: #737373;" class="print-it" value="Оплатить" />
</form>
<?} else {?>
<h3><?=$error?></h3>
<?}?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>