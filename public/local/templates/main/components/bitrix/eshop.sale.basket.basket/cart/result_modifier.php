<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Core\Underscore as _;
use Core\Iblock;

$arResult['ITEMS'] = array_map(function ($items) {
    return array_map(function ($item) {
        // TODO optimize
        $el = _::first(iter\toArray(Iblock::collectElements(CIBlockElement::GetByID($item['PRODUCT_ID']))));
        return array_merge($el, $item);
    }, $items);
}, $arResult['ITEMS']);
