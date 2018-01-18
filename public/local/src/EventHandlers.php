<?php

namespace App;

use CEvent;
use Core\Underscore as _;
use Core\Strings as str;
use CUser;

class EventHandlers {
    static function attach() {
        // see also init.php
        AddEventHandler('main', 'OnBeforeUserRegister', _::func([self::class, 'onBeforeUserRegister']));
        AddEventHandler('main', 'OnAfterUserRegister', _::func([self::class, 'onAfterUserRegister']));
        AddEventHandler('main', 'OnBeforeUserUpdate', _::func([self::class, 'onBeforeUserUpdate']));
    }

    static function onBeforeUserRegister(&$fieldsRef) {
        if (!isset($fieldsRef['LOGIN']) || $fieldsRef['LOGIN'] === Auth::LOGIN_EQ_EMAIL) {
            // email as login
            $fieldsRef['LOGIN'] = $fieldsRef['EMAIL'];
        }
        return $fieldsRef;
    }

    static function onBeforeUserUpdate(&$fieldsRef) {
        $user = CUser::GetByID($fieldsRef['ID'])->GetNext();
        if ($user['LOGIN'] === $user['EMAIL'] && !str::isEmpty($fieldsRef['EMAIL'])) {
            // email as login
            $fieldsRef['LOGIN'] = $fieldsRef['EMAIL'];
        }
        return $fieldsRef;
    }

    static function onAfterUserRegister(&$arFields) {
        // see system.auth.registration form
        $isPartner = _::get($_REQUEST, 'user_type') === 'service-center';
        if (!$isPartner) {
            return $arFields;
        }
        if (0 < $arFields["USER_ID"]){
            $holidayText = "";

            $holiday = App::holidayMode();
            if ($holiday['isEnabled']) {
                $holidayText = "<br><br>Ваша заявка на регистрацию будет рассмотрена <strong>".$holiday['to']."</strong><br><br>";
            }

            $toSend = array(
                'EMAIL' 	=> $arFields['EMAIL'],
                'LOGIN' 	=> $arFields['LOGIN'],
                'PASSWORD'  => $arFields['CONFIRM_PASSWORD'],
                'HOLIDAY'   => $holidayText
            );

            CEvent::Send("NEW_USER2", SITE_ID, $toSend, 'Y');
        }

        return $arFields;
    }
}