<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Результат оплаты");
?><?$APPLICATION->IncludeComponent("bitrix:sale.order.payment.receive", "", array(
	"PAY_SYSTEM_ID" => "7",
	"PERSON_TYPE_ID" => "1"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>