<?php

namespace App;

use CUser;
use iter;
use Bitrix\Sale\OrderUserProperties;
use Core\Strings as str;
use Core\Underscore as _;

class User {
    static function formatFullName($last, $first, $second) {
        $options = [[$last, $first, $second], [$first, $second], [$first, $last], [$first]];
        foreach ($options as $parts) {
            if (_::matches($parts, _::negate([str::class, 'isEmpty']))) {
                return join(' ', $parts);
            }
        }
        return 'Уважаемый пользователь';
    }

    static function fullName(CUser $user) {
        return self::formatFullName($user->GetLastName(), $user->GetFirstName(), $user->GetSecondName());
    }

    static function userProps(CUser $user) {
        $result = OrderUserProperties::getList([
            'order' => ['DATE_UPDATE' => 'DESC'],
            'filter' => [
                'USER_ID' => (int)($user->GetId())
            ],
            'select' => ['*'],
            'limit' => 1
        ]);
        return $result->fetch();
    }

    static function userPropSuggestions(CUser $user) {
        $ent = CUser::GetByID($user->GetID())->GetNext();
        return [
            'ORDER_PROP_FAM' => $ent['LAST_NAME'],
            'ORDER_PROP_IMYA' => $ent['NAME'],
            'ORDER_PROP_OTCHESTVO' => $ent['SECOND_NAME'],
            'ORDER_PROP_PHONE' => $ent['PERSONAL_PHONE'] ?: $ent['WORK_PHONE']
        ];
    }

    // sync profiles

    static function updateFromUserProps(CUser $user, $props) {
        $values = iter\reduce(function ($acc, $prop) {
            $acc[$prop['CODE']] = $prop['VALUE'];
            return $acc;
        }, $props, []);
        $u = new CUser();
        return $u->Update($user->GetID(), [
            'PERSONAL_PHONE' => $values['PHONE'],
        ]);
    }
}