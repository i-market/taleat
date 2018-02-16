<?php

namespace App;

use Bitrix\Iblock\PropertyEnumerationTable;
use Bitrix\Main\Config\Configuration;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\UserTable;
use CEvent;
use Core\Session;
use Core\Underscore as _;
use Core\Strings as str;
use CSubscription;
use CUser;

Loader::includeModule('subscribe');

class EventHandlers {
    /** @var callable[] */
    private static $deferredUntilAfterUpdate = [];

    static function attach() {
        // see also init.php
        AddEventHandler('main', 'OnBeforeUserRegister', _::func([self::class, 'onBeforeUserRegister']));
        AddEventHandler('main', 'OnAfterUserRegister', _::func([self::class, 'onAfterUserRegister']));
        AddEventHandler('main', 'OnBeforeUserUpdate', _::func([self::class, 'onBeforeUserUpdate']));
        AddEventHandler('main', 'OnBeforeEventSend', _::func([self::class, 'onBeforeEventSend']));
        AddEventHandler('iblock', 'OnBeforeIBlockElementUpdate', _::func([self::class, 'onBeforeIBlockElementUpdate']));
        AddEventHandler('iblock', 'OnAfterIBlockElementUpdate', _::func([self::class, 'onAfterIBlockElementUpdate']));
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

    static function onBeforeIBlockElementUpdate($fieldsBefore) {
        $id = $fieldsBefore['ID'];
        if ($id && $fieldsBefore['IBLOCK_ID'] == IB_REPORTS) {
            $elem = Iblock::iterElements(\CIBlockElement::GetByID($id))->current();
            self::$deferredUntilAfterUpdate[] =
                function ($fields) use ($elem) {
                    if ($fields['ID'] == $elem['ID']) {
                        $statusProp = 58;
                        $statusId = $fields['PROPERTY_VALUES'][$statusProp][0]['VALUE'];
                        $statusEnum = PropertyEnumerationTable::getList([
                            'filter' => ['PROPERTY_ID' => $statusProp, 'ID' => $statusId]
                        ])->fetch();
                        $status = $statusEnum['VALUE'];
                        $comment = $fields['PREVIEW_TEXT'];
                        $hasChanged = $status !== $elem['PROPERTIES']['STATUS']['VALUE']
                            || $comment !== $elem['PREVIEW_TEXT'];
                        if ($hasChanged) {
                            $userId = $elem['PROPERTIES']['USER']['VALUE'];
                            $res = CEvent::Send(Events::TEH_ZAKL_UPDATE, App::SITE_ID, [
                                'EMAIL_TO' => CUser::GetByID($userId)->GetNext()['EMAIL'],
                                'NUMBER' => $elem['PROPERTIES']['NUMER']['VALUE'],
                                'STATUS' => $status,
                                'COMMENT' => !str::isEmpty($comment) ? "Комментарий: {$comment}" :  ''
                            ]);
                            App::getInstance()->assert($res);
                        }
                    }
                };
        }
        return $fieldsBefore;
    }
    
    static function onAfterIBlockElementUpdate($fields) {
        foreach (self::$deferredUntilAfterUpdate as $f) {
            $f($fields);
        }
        self::$deferredUntilAfterUpdate = [];
        return $fields;
    }

    static function onBeforeUserRegister(&$fieldsRef) {
        global $APPLICATION;
        if (!isset($fieldsRef['LOGIN']) || $fieldsRef['LOGIN'] === Auth::LOGIN_EQ_EMAIL) {
            $isEmailUnique = UserTable::getCount(['EMAIL' => $fieldsRef['EMAIL']]) === 0;
            if (!$isEmailUnique) {
                $APPLICATION->ThrowException('Аккаунт с таким e-mail адресом уже существует');
                return false;
            }
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
        if (0 < $arFields['USER_ID']) {
            // see system.auth.registration form
            $isUnconfirmedPartner = _::get($_REQUEST, 'user_type') === 'service-center';
            if ($isUnconfirmedPartner) {
                Session::addFlash(['type' => 'success', 'text' => Auth::partnerConfirmationPendingMsg()]);
                CUser::AppendUserGroup($arFields['USER_ID'], [Auth::unconfirmedPartnerId()]);

                CEvent::Send(Events::NEW_UNCONFIRMED_PARTNER, App::SITE_ID, [
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

            $holidayText = "";
            $holiday = App::holidayMode();
            if ($isUnconfirmedPartner && $holiday['isEnabled']) {
                $holidayText = "<br><br>Ваша заявка на регистрацию будет рассмотрена <strong>" . $holiday['to'] . "</strong><br><br>";
            }
            $toSend = array(
                'EMAIL' => $arFields['EMAIL'],
                // TODO bug: `$arFields['LOGIN'] === Auth::LOGIN_EQ_EMAIL` even when login is changed in `onBeforeUserRegister`
                // email as login
                'LOGIN' => $arFields['EMAIL'],
                'PASSWORD' => $arFields['CONFIRM_PASSWORD'], // mailing plain text passwords is a bad security practice
                'HOLIDAY' => $holidayText
            );
            CEvent::Send("NEW_USER2", App::SITE_ID, $toSend, 'Y');
        }
        return $arFields;
    }
}