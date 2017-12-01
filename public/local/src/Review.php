<?php

namespace App;

use CEvent;
use CIBlockElement;

class Review {
    static function successResult() {
        return [
            'success' => true,
            'message' => [
                'type' => 'success',
                'text' => 'Спасибо! Ваш отзыв появится после проверки модератором.'
            ]
        ];
    }

    static function create($params) {
        // TODO error handling
        $el = new CIBlockElement;
        $PROP = array();
        $PROP["CITY"] = $params["CITY"];
        $PROP["ORDER_NUM"] = $params["ORDER_NUM"];
        $arLoadProductArray = Array(
            "IBLOCK_ID" => 5,
            "NAME" => $params["NAME"],
            "ACTIVE" => "N",
            "PREVIEW_TEXT" => $params["TEXT"],
            "PROPERTY_VALUES" => $PROP,
            "DATE_ACTIVE_FROM" => date('d.m.Y')
        );
        if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
            $arSendFields = array(
                "LINK" => "/bitrix/admin/iblock_element_edit.php?WF=Y&ID=" . $PRODUCT_ID . "&type=otzivi&lang=ru&IBLOCK_ID=5&find_section_section=0"
            );
            CEvent::Send("ADD_OTZIV", "s1", $arSendFields);
        }
        return self::successResult();
    }
}