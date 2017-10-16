<?php

namespace App;

use CFile;
use CIBlockSection;
use CPrice;
use Core\Nullable as nil;
use Core\Underscore as _;
use Core\Strings as str;

class Product {
    static function thumbnail($elem) {
        $arItem = $elem;
        $rsFile = CFile::GetByID($arItem["PROPERTIES"]["OPT_DETAIL_PICTURE"]["VALUE"][4]);
        $arFile = $rsFile->Fetch();
        if($arFile["HEIGHT"]<131) {
            return CFile::GetByID($arItem["PROPERTIES"]["OPT_DETAIL_PICTURE"]["VALUE"][4])->Fetch();
         } else {
            return CFile::GetByID($arItem["PROPERTIES"]["OPT_DETAIL_PICTURE"]["VALUE"][3])->Fetch();
         }
    }

    static function basePrice($productId) {
        return CPrice::GetBasePrice($productId);
    }

    static function brand($elem) {
        $manufacturer = _::get($elem, 'PROPERTIES.MANUFACTURER.VALUE');
        if (!str::isEmpty($manufacturer)) {
            return $manufacturer;
        }
        $result = CIBlockSection::GetList([], [
            'HAS_ELEMENT' => $elem['ID'],
            'DEPTH_LEVEL' => 1,
            'IBLOCK_ID' => $elem['IBLOCK_ID'],
            'ACTIVE' => 'Y'
        ], false, ['NAME']);
        return nil::map($result->Fetch(), function ($ent) { return $ent['NAME']; });
    }
}