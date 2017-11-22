<?php

namespace App;

use Core\Strings as str;
use CUser;

class Auth {
    const PARTNER_GROUP = 9;

    static function restrictAccess() {
        global $APPLICATION, $USER;
        if ($USER->IsAdmin()) {
            return;
        }
        $dir = $APPLICATION->GetCurDir();
        if (str::startsWith($dir, '/partneram/')) {
            if (!$USER->IsAuthorized()) {
                $APPLICATION->AuthForm('');
            } else {
                $isPartner = in_array(self::PARTNER_GROUP, CUser::GetUserGroup($USER->GetID()));
                if (!$isPartner) {
                    $APPLICATION->AuthForm([
                        'TYPE' => 'ERROR',
                        'MESSAGE' => 'Ваш аккаунт еще не подтвержден администратором'
                    ]);
                }
            }
        }
    }

    static function links() {
        return [
            'registerLink' => '/login/?register=yes&backurl=%2F',
            'profileLink' => '/personal/',
            'loginLink' => '/login/?login=yes&backurl=%2F',
            'logoutLink' => '/?logout=yes'
        ];
    }
}