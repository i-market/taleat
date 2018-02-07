<?php

namespace App;

use CGroup;
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

    static function unconfirmedPartnerId() {
        $ret = CGroup::GetIDByCode(UserGroup::UNCONFIRMED_PARTNER);
        App::getInstance()->assert($ret);
        return $ret;
    }

    static function isUnconfirmedPartner(CUser $user) {
        // return false if `isPartner`? or don't?
        return $user->IsAuthorized() && in_array(self::unconfirmedPartnerId(), CUser::GetUserGroup($user->GetID()));
    }

    static function partnerConfirmationPendingMsg() {
        return 'Учетная запись сервисного центра становится активной только после подтверждения администратором';
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
                    'MESSAGE' => self::partnerConfirmationPendingMsg()
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