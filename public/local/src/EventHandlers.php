<?php

namespace App;

use Bitrix\Main\Config\Configuration;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use CEvent;
use Core\Session;
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
        AddEventHandler('main', 'onBeforeEventSend', _::func([self::class, 'onBeforeEventSend']));
    }

    static function onBeforeEventSend(&$fieldsRef, &$templateRef) {
        $app = Configuration::getValue('app');
        $overrideSender = _::get($app, 'override_email_sender');
        $overrideDefaultEmailFrom = _::get($app, 'override_default_email_from');

        if (!isset($fieldsRef['ADMIN_EMAIL'])) {
            $fieldsRef['ADMIN_EMAIL'] = _::get($app, 'admin_email', Option::get('main', 'email_from'));
        }
        if ($overrideDefaultEmailFrom) {
            $fieldsRef['DEFAULT_EMAIL_FROM'] = $overrideDefaultEmailFrom;
        }
        if ($overrideSender) {
            $templateRef['EMAIL_FROM'] = $overrideSender;
        }
        return $fieldsRef;
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
        $isUnconfirmedPartner = _::get($_REQUEST, 'user_type') === 'service-center';
        if ($isUnconfirmedPartner) {
            Session::addFlash(['type' => 'success', 'text' => Auth::partnerConfirmationPendingMsg()]);
            if (0 < $arFields["USER_ID"]) {
                CUser::AppendUserGroup($arFields['USER_ID'], [Auth::unconfirmedPartnerId()]);

                CEvent::Send(Events::NEW_UNCONFIRMED_PARTNER, SITE_ID, [
                    'USER_ID' => $arFields['USER_ID'],
                    'EMAIL' => $arFields['EMAIL'],
                    'WORK_COMPANY' => $arFields['WORK_COMPANY']
                ]);

                // TODO sub only when the user becomes a confirmed partner?
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
        }

        $holidayText = "";
        $holiday = App::holidayMode();
        if ($isUnconfirmedPartner && $holiday['isEnabled']) {
            $holidayText = "<br><br>Ваша заявка на регистрацию будет рассмотрена <strong>" . $holiday['to'] . "</strong><br><br>";
        }
        $toSend = array(
            'EMAIL' => $arFields['EMAIL'],
            'LOGIN' => $arFields['LOGIN'],
            'PASSWORD' => $arFields['CONFIRM_PASSWORD'], // mailing plain text passwords is a bad security practice
            'HOLIDAY' => $holidayText
        );
        CEvent::Send("NEW_USER2", SITE_ID, $toSend, 'Y');

        return $arFields;
    }
}