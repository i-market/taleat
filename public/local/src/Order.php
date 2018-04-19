<?php

namespace App;

use Core\Underscore as _;

class Order {
    /** самовывоз */
    const LOCAL_PICKUP = '2';
    const CSE = '27';
    const RU_POST = '28';

    static function isPayable($order) {
        return $order['STATUS_ID'] === OrderStatus::ACCEPTED
            && $order['PAYED'] !== 'Y'
            && $order['CANCELED'] !== 'Y';
    }

    // TODO hack: clean up locations
    static function locationsUniqueBy($xs, callable $f) {
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
    }

    // TODO hack
    static function filterDeliveryServices($xs, $location) {
        $moscow = [5773, 6322];
        $stPetersburg = [6002, 6333];
        $rusPost = 24;
        if (in_array($location, $moscow)) {
            return _::remove($xs, $rusPost);
        } else {
            return $xs;
        }
    }
    
    static function deliveryServiceOrgInfo($id) {
        $data = [
            self::RU_POST => [
                'name' => 'Почта России',
                'url' => 'https://www.pochta.ru/'
            ],
            self::CSE => [
                'name' => 'Курьерская служба КСЭ',
                'url' => 'http://www.cse.ru/lang-rus/'
            ]
        ];
        return _::get($data, $id);
    }
}
