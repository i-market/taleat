<?php

namespace App;

use Bitrix\Iblock\Component\Tools;
use CFile;
use CIBlockSection;
use Core\Nullable as nil;
use Core\Strings as str;
use Core\Underscore as _;
use CPrice;

class Product {
    // `PROP` property values
    const HAS_DISCOUNT = 'Со скидкой';

    const BASE_PRICE = 'catalog_price_1';
    const CURRENCY = 'RUB';

    const IMAGE_MEDIUM = [500, 500];
    const IMAGE_SMALL = [100, 100];

    static function elementUrl($elem) {
        return fn_get_chainpath($elem['IBLOCK_ID'], $elem['IBLOCK_SECTION_ID']).$elem['CODE'].'.html';
    }

    static function sectionUrl($section) {
        return fn_get_chainpath($section['IBLOCK_ID'], $section['ID']);
    }

    // TODO refactor: we shouldn't need this when we fix all the paths
    static function pathStartsWith($_prefix, $_subject) {
        $path = function ($str) {
            return array_filter(explode('/', $str), function ($s) {
                return !str::isEmpty($s);
            });
        };
        $prefix  = $path($_prefix);
        $subject = $path($_subject);
        $zipped = array_map(null, $prefix, $subject);
        $matching = _::takeWhile($zipped, function ($pair) {
            list($a, $d) = $pair;
            return $a === $d;
        });
        return count($matching) === count($prefix);
    }

    static function thumbnail($elem) {
        App::getInstance()->assert(array_key_exists('DETAIL_PICTURE', $elem) && isset($elem["PROPERTIES"]["OPT_DETAIL_PICTURE"]),
            'illegal argument');
        // TODO what's the algorithm here?
        $arFile = CFile::GetFileArray($elem["PROPERTIES"]["OPT_DETAIL_PICTURE"]["VALUE"][4]);
        if($arFile["HEIGHT"]<131) {
            return CFile::GetFileArray($elem["PROPERTIES"]["OPT_DETAIL_PICTURE"]["VALUE"][4]);
         } else {
            $optPic = CFile::GetFileArray($elem["PROPERTIES"]["OPT_DETAIL_PICTURE"]["VALUE"][3]);
            if (is_array($optPic)) {
                return $optPic;
            } else {
                $elemRef = $elem;
                if (is_numeric($elem['DETAIL_PICTURE'])) {
                    Tools::getFieldImageData($elemRef, ['DETAIL_PICTURE'], Tools::IPROPERTY_ENTITY_ELEMENT, 'IPROPERTY_VALUES');
                }
                return $elemRef['DETAIL_PICTURE'];
            }
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