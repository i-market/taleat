<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Core\Underscore as _;

$groups = _::group($arResult['ITEMS'], function ($item) {
    return is_array($item['PREVIEW_PICTURE']) ? 'SLIDER' : 'REST';
});
$arResult += $groups;
