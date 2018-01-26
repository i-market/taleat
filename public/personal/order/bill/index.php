<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Счет на оплату");

use App\View as v;

// TODO /order/bill/ page
LocalRedirect(v::path('personal/order'));

?><?$APPLICATION->IncludeComponent("imarket:sale.order.ajax", "bill", Array(
	"PAY_FROM_ACCOUNT" => "Y",	// Позволять оплачивать с внутреннего счета
	"COUNT_DELIVERY_TAX" => "N",	// Рассчитывать налог для доставки
	"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",	// Рассчитывать скидку для каждой позиции (на все количество товара)
	"ONLY_FULL_PAY_FROM_ACCOUNT" => "N",	// Позволять оплачивать с внутреннего счета только в полном объеме
	"ALLOW_AUTO_REGISTER" => "Y",	// Оформлять заказ с автоматической регистрацией пользователя
	"SEND_NEW_USER_NOTIFY" => "Y",	// Отправлять пользователю письмо, что он зарегистрирован на сайте
	"DELIVERY_NO_AJAX" => "N",	// Рассчитывать стоимость доставки сразу
	"DELIVERY_NO_SESSION" => "N",	// Проверять сессию при оформлении заказа
	"TEMPLATE_LOCATION" => ".default",	// Шаблон местоположения
	"DELIVERY_TO_PAYSYSTEM" => "d2p",	// Последовательность оформления
	"USE_PREPAYMENT" => "N",	// Использовать предавторизацию для оформления заказа (PayPal Express Checkout)
	"PROP_1" => "",	// Не показывать свойства для типа плательщика "Физическое лицо" (s1)
	"PROP_2" => "",	// Не показывать свойства для типа плательщика "Юридическое лицо" (s1)
	"PATH_TO_BASKET" => "/personal/cart/",	// Страница корзины
	"PATH_TO_PERSONAL" => "/personal/order/",	// Страница персонального раздела
	"PATH_TO_PAYMENT" => "/personal/order/payment/",	// Страница подключения платежной системы
	"PATH_TO_AUTH" => "/auth/",	// Страница авторизации
	"SET_TITLE" => "N",	// Устанавливать заголовок страницы
	"DISPLAY_IMG_WIDTH" => "90",	// Ширина картинки товара
	"DISPLAY_IMG_HEIGHT" => "90",	// Высота картинки товара
	),
	false
);?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>