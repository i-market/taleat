<?php

namespace App;

use Bitrix\Iblock\Component\Tools;
use Bitrix\Iblock\ElementTable;
use Bitrix\Main\UserFieldTable;
use CCurrencyLang;
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

    // image dimension constraints
    const IMAGE_FULL = [1000, 1000];
    const IMAGE_MEDIUM = [500, 500];
    const IMAGE_MEDIUM_SMALL = [300, 300];
    const IMAGE_SMALL = [100, 100];
    const BRAND_LOGO = [300, 80];

    static function recommended($section) {
        $sect = CIBlockSection::GetList(['SORT' => 'ASC'], [
            'IBLOCK_ID' => $section['IBLOCK_ID'],
            'ID' => $section['ID']
        ], false, ['UF_RECOMMENDED'])->GetNext();
        // TODO optimize n+1 query
        $products = _::map($sect['UF_RECOMMENDED'], function ($id) {
            return \CIBlockElement::GetByID($id)->GetNext();
        });
        return $products;
    }

    static function elementUrl($elem) {
        return fn_get_chainpath($elem['IBLOCK_ID'], $elem['IBLOCK_SECTION_ID']).$elem['CODE'].'.html';
    }

    static function sectionUrl($section) {
        return fn_get_chainpath($section['IBLOCK_ID'], $section['ID']);
    }

    // TODO refactor: we won't need this when we fix all the paths
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
        App::getInstance()->assert(array_key_exists('DETAIL_PICTURE', $elem), 'illegal argument');
        $elemRef = $elem;
        Tools::getFieldImageData($elemRef, ['DETAIL_PICTURE', 'PREVIEW_PICTURE'],
            Tools::IPROPERTY_ENTITY_ELEMENT, 'IPROPERTY_VALUES');
        // TODO refactor. preview pic is missing for bitrix:catalog.products.viewed elements.
        return ($elemRef['~PREVIEW_PICTURE'] ? $elemRef['PREVIEW_PICTURE'] : null) ?: $elemRef['DETAIL_PICTURE'];
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
        $images = _::clean(array_merge([$elem['DETAIL_PICTURE']], array_map([CFile::class, 'GetFileArray'], $fileIds)));
        return array_map(function ($img) {
            if (!isset($img['ALT'])) {
                $img['ALT'] = _::get($img, 'DESCRIPTION');
            }
            return $img;
        }, $images);
    }

    private static function updateCurrency($price, $trimWhitespace, callable $f) {
        $ws = $trimWhitespace ? '\s*' : '';
        return preg_replace_callback('/'.$ws.'\b\pL+\.?$/u', function ($m) use ($f, $trimWhitespace) {
            $text = $trimWhitespace ? trim($m[0]) : $m[0];
            return $f($text);
        }, $price);
    }

    static function wrapCurrency($price, $trimWhitespace = false) {
        return self::updateCurrency($price, $trimWhitespace, function ($text) {
            return "<span>{$text}</span>";
        });
    }

    static function wrapAmount($price, $trimWhitespace = false) {
        return '<span>'.self::updateCurrency($price, $trimWhitespace, function ($text) {
            return "</span>{$text}";
        });
    }
}