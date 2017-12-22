<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Core\Underscore as _;
use Core\Util;

$arResult['ITEMS'] = _::map($arResult['ITEMS'], function ($item) {
    return _::set($item, 'LAT_LONG', Util::parseLatLong($item['PROPERTIES']['LAT_LONG']['VALUE']));
});
