<?php

namespace App;

use Core\Strings as str;
use CUser;

class Auth {
    const PARTNER_GROUP = 9;

    static function restrictAccess() {
        global $APPLICATION, $USER;
        $bypass = function () use (&$USER) {
            return $USER->IsAdmin();
        };
        $dir = $APPLICATION->GetCurDir();
        $authDir = '/partneram/auth/';
        if (str::startsWith($dir, '/partneram/') && $dir !== $authDir) {
            $isPartner =
                $USER->IsAuthorized()
                && in_array(self::PARTNER_GROUP, CUser::GetUserGroup($USER->GetID()));
            if (!($isPartner || $bypass())) {
                // TODO backurl?
                // TODO bx-auth-note
                LocalRedirect($authDir);
            }
        }
    }

    static function links() {
        return [
            // TODO register backurl
            'registerLink' => '/login/?register=yes&backurl=%2Flogin%2F',
            'profileLink' => '/personal/',
            'loginLink' => '/login/?login=yes',
            'logoutLink' => '/?logout=yes'
        ];
    }
}