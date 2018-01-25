<?php

namespace App;

use CUser;
use CSite;
use App\View as v;

class Auth {
    /** sentinel value */
    const LOGIN_EQ_EMAIL = 'b96c3bbff883a82d6199f59ebcaaa601';
    const PARTNER_GROUP = 9;

    static function isPartner(CUser $user) {
        return $user->IsAuthorized() && in_array(self::PARTNER_GROUP, CUser::GetUserGroup($user->GetID()));
    }

    static function hasAdminPanelAccess(CUser $user) {
        return $user->IsAdmin();
    }

    static function restrictAccess() {
        global $APPLICATION, $USER;
        if ($USER->IsAdmin()) {
            return;
        }
        if (CSite::InDir(v::path('admin'))) {
            if (!$USER->IsAuthorized()) {
                $APPLICATION->AuthForm('');
            } elseif (!self::hasAdminPanelAccess($USER)) {
                $APPLICATION->AuthForm([
                    'TYPE' => 'ERROR',
                    'MESSAGE' => 'Недостаточно прав'
                ]);
            }
        } elseif (CSite::InDir(v::path('partneram'))) {
            if (!$USER->IsAuthorized()) {
                $APPLICATION->AuthForm('');
            } elseif (!self::isPartner($USER)) {
                $APPLICATION->AuthForm([
                    'TYPE' => 'ERROR',
                    'MESSAGE' => 'Ваш партнерский аккаунт еще не подтвержден администратором'
                ]);
            }
        }
    }

    static function links() {
        return [
            'registerLink' => '/login/?register=yes&backurl=%2F',
            'profileLink' => '/personal/order/',
            'loginLink' => '/login/?login=yes&backurl=%2F',
            'logoutLink' => '/?logout=yes'
        ];
    }
}