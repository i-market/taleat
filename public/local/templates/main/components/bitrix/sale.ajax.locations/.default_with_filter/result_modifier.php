<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

// TODO hack: clean up locations
$uniqueBy = function ($xs, callable $f) {
    $keep = [5773]; // locations with dependencies, keep them
    $ret = [];
    $seen = [];
    foreach ($xs as $x) {
        if (!in_array($f($x), $seen) || in_array($x['ID'], $keep)) {
            $ret[] = $x;
        }
        $seen[] = $f($x);
    }
    return $ret;
};

$arResult['REGION_LIST'] = $uniqueBy($arResult['REGION_LIST'], function ($x) { return $x['NAME']; });
$arResult['CITY_LIST'] = $uniqueBy($arResult['CITY_LIST'], function ($x) { return $x['CITY_NAME']; });
