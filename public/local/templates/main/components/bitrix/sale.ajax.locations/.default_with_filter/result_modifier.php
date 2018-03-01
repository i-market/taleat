<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\Order;

$arResult['REGION_LIST'] = Order::locationsUniqueBy($arResult['REGION_LIST'], function ($x) { return $x['NAME']; });
$arResult['CITY_LIST'] = Order::locationsUniqueBy($arResult['CITY_LIST'], function ($x) { return $x['CITY_NAME']; });
