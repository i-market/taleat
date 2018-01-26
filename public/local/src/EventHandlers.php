<?php

namespace App;

use Bitrix\Main\Loader;
use CEvent;
use Core\Underscore as _;
use Core\Strings as str;
use CSubscription;
use CUser;

Loader::includeModule('subscribe');

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
        if ($isPartner) {
            if (0 < $arFields["USER_ID"]) {
                $holidayText = "";

                $holiday = App::holidayMode();
                if ($holiday['isEnabled']) {
                    $holidayText = "<br><br>Ваша заявка на регистрацию будет рассмотрена <strong>" . $holiday['to'] . "</strong><br><br>";
                }

                $toSend = array(
                    'EMAIL' => $arFields['EMAIL'],
                    'LOGIN' => $arFields['LOGIN'],
                    'PASSWORD' => $arFields['CONFIRM_PASSWORD'],
                    'HOLIDAY' => $holidayText
                );

                CEvent::Send("NEW_USER2", SITE_ID, $toSend, 'Y');
            }
            $sub = new CSubscription();
            $res = $sub->Add([
                'USER_ID' => $arFields['USER_ID'],
                'ACTIVE' => 'Y',
                'FORMAT' => App::PARTNER_NEWSLETTER_FORMAT,
                'EMAIL' => $arFields['EMAIL'],
                'CONFIRMED' => 'Y',
                'SEND_CONFIRM' => 'N',
                'RUB_ID' => [App::PARTNER_NEWSLETTER_ID],
                'ALL_SITES' => 'Y',
            ]);
            App::getInstance()->assert($res, 'partner newsletter subscription issue');
        }
        return $arFields;
    }
}