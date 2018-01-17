<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Заказы");
$APPLICATION->SetPageProperty('layout', 'personal');
?>
<?
$_REQUEST['show_all'] = 'Y'; // used in bitrix:sale.personal.order.list
$_REQUEST['show_all_is_set_in'] = __FILE__; // hopefully no one will have to track this down. let's leave a hint.
?>
<?$APPLICATION->IncludeComponent(
    "bitrix:sale.personal.order",
    "orders",
    array(
        "PROP_1" => array(
            0 => "6",
        ),
        "PROP_2" => array(
            0 => "18",
        ),
        "SEF_MODE" => "Y",
        "SEF_FOLDER" => "/personal/order/",
        "ORDERS_PER_PAGE" => "10",
        "PATH_TO_PAYMENT" => "/personal/order/payment/",
        "PATH_TO_BASKET" => "/personal/cart/",
        "SET_TITLE" => "Y",
        "SAVE_IN_SESSION" => "N",
        "NAV_TEMPLATE" => "arrows",
        "STATUS_COLOR_N" => "gray",
        "STATUS_COLOR_O" => "red",
        "STATUS_COLOR_A" => "yellow",
        "STATUS_COLOR_P" => "green",
        "STATUS_COLOR_F" => "green",
        "COMPONENT_TEMPLATE" => "new",
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600",
        "CACHE_GROUPS" => "Y",
        "CUSTOM_SELECT_PROPS" => array(
        ),
        "HISTORIC_STATUSES" => array(
            0 => "F",
        ),
        "STATUS_COLOR_S" => "gray",
        "STATUS_COLOR_PSEUDO_CANCELLED" => "red",
        "SEF_URL_TEMPLATES" => array(
            "list" => "index.php",
            "detail" => "detail/#ID#/",
            "cancel" => "cancel/#ID#/",
        )
    ),
    false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>