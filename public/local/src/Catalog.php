<?php

namespace App;

use CSaleBasket;
use Core\Underscore as _;

class Catalog {
    function dispatch($action, $params) {
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
}