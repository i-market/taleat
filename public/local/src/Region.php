<?php

namespace App;

use CIBlockElement;
use CIBlockProperty;
use Core\Underscore as _;

class Region {
    static function context($params) {
        $items = self::items();
        if (isset($params['selected'])) {
            $cityByBrand = _::reduce(_::clean(explode(',', $params['selected'])), function ($acc, $sel) {
                list($brand, $city) = explode(':', $sel);
                return _::set($acc, $brand, $city);
            }, []);
            $items = _::map($items, function ($item) use ($cityByBrand) {
                $city = _::get($cityByBrand, $item['brand']['ID']);
                if ($city !== null) {
                    $item['services'] = self::services($item['brand']['ID'], $city);
                }
                return $item;
            });
        } else {
            $cityByBrand = [];
        }
        return [
            'items' => $items,
            'selected' => $cityByBrand
        ];
    }

    static function services($brandId, $cityId) {
        $result = CIBlockElement::GetList(Array("ID","NAME","PREVIEW_TEXT"), Array("IBLOCK_ID"=>7, "ACTIVE"=>"Y", "PROPERTY_CITY"=>$cityId, "PROPERTY_BRANDS"=>$brandId));
        return Iblock::iter($result);
    }

    static function items() {
        global $APPLICATION;
        $ret = [];
        ?>
        <?$db_enum_list = CIBlockProperty::GetPropertyEnum("BRANDS", Array("sort "=>"asc"), Array("IBLOCK_ID"=>7));
        while($ar_enum_list = $db_enum_list->GetNext()){
            if($APPLICATION->GetProperty("hide_braun_beauty") == "Y" && $ar_enum_list["ID"] == 32) continue;
            if($APPLICATION->GetProperty("hide_braun_kitchen") == "Y" && $ar_enum_list["ID"] == 33) continue;
            if($APPLICATION->GetProperty("hide_babyliss") == "Y" && $ar_enum_list["ID"] == 34) continue;
            $arCity = array();
            $res = CIBlockElement::GetList(Array("NAME"=>"ASC"), Array("IBLOCK_ID"=>6, "ACTIVE"=>"Y"), false, false ,array());
            $i = 0;
            while($ob = $res->Fetch()){
                $res2 = CIBlockElement::GetList(Array("PROPERTY_CITY.NAME"=>"ASC"), Array("IBLOCK_ID"=>7, "ACTIVE"=>"Y", "PROPERTY"=>array("CITY"=>$ob["ID"],"BRANDS"=>$ar_enum_list["ID"])));
                $i2 = 0;
                while($ob2 = $res2->Fetch()){
                    $arCity[$i]["ID"] = $ob["ID"];
                    $arCity[$i]["NAME"] = $ob["NAME"];
                    $arCity[$i]["SERVICE"][$i2]["ID"] = $ob2["ID"];
                    $arCity[$i]["SERVICE"][$i2]["NAME"] = $ob2["NAME"];
                    $i2++;
                }
                $i++;
            }?>
            <?
            $brand = $ar_enum_list;
            $cities = $arCity;
            $path = "/include/textbrand" . $brand["ID"] . ".php";
            $ret[] = ['brand' => $brand, 'cities' => $cities, 'brand_desc_path' => $path];
            ?>
        <? } ?>
        <?
        return $ret;
    }
}