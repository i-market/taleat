<?php

namespace App;

use CSaleBasket;
use Core\Underscore as _;

class Cart {
    function runEffects($action, $params) {
        // update product quantity
        foreach (_::get($params, 'QUANTITY', []) as $id => $quantity) {
            $basket = new CSaleBasket();
            $result = $basket->Update($id, ['QUANTITY' => $quantity]);
            if (!$result) {
                // TODO render $APPLICATION->LAST_ERROR;
                return;
            }
        }
        switch ($action) {
            case 'deleteAll':
                $result = CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());
                App::getInstance()->assert($result);
                break;
        }
    }

    function index() {
        global $APPLICATION;
        // TODO run effects on POST only
        $this->runEffects(_::get($_REQUEST, 'action'), $_REQUEST);

        $APPLICATION->IncludeComponent(
            "bitrix:eshop.sale.basket.basket",
            "cart",
            array(
                "COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
                "COLUMNS_LIST" => array(
                    0 => "NAME",
                    1 => "PROPS",
                    2 => "PRICE",
                    3 => "QUANTITY",
                    4 => "DELETE",
                ),
                "AJAX_MODE" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "AJAX_OPTION_HISTORY" => "N",
                "PATH_TO_ORDER" => "/personal/order/make/",
                "HIDE_COUPON" => "Y",
                "QUANTITY_FLOAT" => "N",
                "PRICE_VAT_SHOW_VALUE" => "Y",
                "USE_PREPAYMENT" => "N",
                "SET_TITLE" => "N",
                "AJAX_OPTION_ADDITIONAL" => "",
                "COMPONENT_TEMPLATE" => ".default"
            ),
            false
        );
    }
}