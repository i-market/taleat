<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

// TODO hack: clean up locations
$uniqueByName = function ($xs) {
    $keep = [5773]; // locations with dependencies, keep them
    $ret = [];
    $seen = [];
    foreach ($xs as $x) {
        if (!in_array($x['NAME'], $seen) || in_array($x['ID'], $keep)) {
            $ret[] = $x;
        }
        $seen[] = $x['NAME'];
    }
    return $ret;
};

$arResult['REGION_LIST'] = $uniqueByName($arResult['REGION_LIST']);
$arResult['CITY_LIST'] = $uniqueByName($arResult['CITY_LIST']);
