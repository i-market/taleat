<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Core\Underscore as _;

$arResult['SORT'] = function ($sections, $order) {
    return _::sort($sections, function($section) use ($order) {
        $idx = array_search($section['CODE'], $order);
        return is_numeric($idx) ? $idx : $section['SORT'];
    });
};
