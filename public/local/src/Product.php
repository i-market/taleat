<?php

namespace App;

use CFile;
use CIBlockSection;
use CPrice;
use Core\Nullable as nil;
use Core\Underscore as _;
use Core\Strings as str;

class Product {
    // `PROP` property values
    const HAS_DISCOUNT = 'Со скидкой';

    const CURRENCY = 'RUB';
    const IMAGE_MEDIUM = [500, 500];
    const IMAGE_SMALL = [100, 100];

    static function thumbnail($elem) {
        $arItem = $elem;
        // TODO what's the algorithm here?
        $rsFile = CFile::GetByID($arItem["PROPERTIES"]["OPT_DETAIL_PICTURE"]["VALUE"][4]);
        $arFile = $rsFile->Fetch();
        if($arFile["HEIGHT"]<131) {
            return CFile::GetByID($arItem["PROPERTIES"]["OPT_DETAIL_PICTURE"]["VALUE"][4])->Fetch();
         } else {
            $optPic = CFile::GetByID($arItem["PROPERTIES"]["OPT_DETAIL_PICTURE"]["VALUE"][3])->Fetch();
            return $optPic !== null ? $optPic : $elem['DETAIL_PICTURE'];
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

    static function galleryImages($elem) {
        $fileIds = _::get($elem, 'PROPERTIES.MORE_PHOTO.VALUE') ?: [];
        $images = array_merge([$elem['DETAIL_PICTURE']], array_map([CFile::class, 'GetFileArray'], $fileIds));
        return array_map(function ($img) {
            if (!isset($img['ALT'])) {
                $img['ALT'] = _::get($img, 'DESCRIPTION');
            }
            return $img;
        }, $images);
    }
}