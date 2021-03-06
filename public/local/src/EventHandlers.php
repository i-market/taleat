<?php

namespace App;

use Bitrix\Iblock\PropertyEnumerationTable;
use Bitrix\Main\Config\Configuration;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\UserTable;
use Bitrix\Sale\Delivery\Services;
use CEvent;
use Core\Session;
use Core\Underscore as _;
use Core\Strings as str;
use CSaleBasket;
use CSubscription;
use CUser;
use iter;
use Bitrix\Sale as sale;

Loader::includeModule('subscribe');

class EventHandlers {
    /** @var callable[] */
    private static $deferredUntilAfterUpdate = [];
    /** @var callable[] */
    private static $deferredUntilAfterUserAdd = [];

    static function attach() {
        // see also: init.php
        AddEventHandler('main',   'OnBeforeUserLogin',           _::func([self::class, 'onBeforeUserLogin']));
        AddEventHandler('main',   'OnBeforeUserRegister',        _::func([self::class, 'onBeforeUserRegister']));
        AddEventHandler('main',   'OnBeforeUserAdd',             _::func([self::class, 'onBeforeUserAdd']));
        AddEventHandler('main',   'OnAfterUserAdd',              _::func([self::class, 'onAfterUserAdd']));
        AddEventHandler('main',   'OnAfterUserRegister',         _::func([self::class, 'onAfterUserRegister']));
        AddEventHandler('main',   'OnBeforeUserUpdate',          _::func([self::class, 'onBeforeUserUpdate']));
        AddEventHandler('main',   'OnBeforeEventSend',           _::func([self::class, 'onBeforeEventSend']));
        AddEventHandler('iblock', 'OnBeforeIBlockElementUpdate', _::func([self::class, 'onBeforeIBlockElementUpdate']));
        AddEventHandler('iblock', 'OnAfterIBlockElementUpdate',  _::func([self::class, 'onAfterIBlockElementUpdate']));
        AddEventHandler('sale',   'OnOrderNewSendEmail',         _::func([self::class, 'onOrderNewSendEmail']));
        AddEventHandler('sale',   'OnSaleComponentOrderOneStepComplete',
            _::func([self::class, 'onSaleComponentOrderOneStepComplete']));

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

        // TODO hack
        if ($templateRef['EVENT_NAME'] === 'NEW_USER' && self::isPartnerSignup()) {
            return false;
        }

        // TODO hack
        if ($templateRef['EVENT_NAME'] === 'SALE_ORDER_CANCEL'
            && $templateRef['EMAIL_TO'] === '#SALE_EMAIL#'
            && defined('ADMIN_SECTION') && ADMIN_SECTION) {
            return false;
        }

        // TODO hack
        // see /bitrix/components/imarket/sale.order.ajax/component.php
        if ($templateRef['EVENT_NAME'] === 'SALE_NEW_ORDER') {
            $holidayText = "";
            $holiday = App::holidayMode();
            if ($holiday['isEnabled']) {
                $holidayText = "<strong>Заказ будет обработан ".$holiday['to']."</strong><br><br>";
            }
            $order = sale\Order::load($fieldsRef['ORDER_ID']);
            App::getInstance()->assert($order);
            /** @var sale\Payment $payment */
            $payment = $order->getPaymentCollection()->current();
            $items = iter\toArray(Iblock::iter((new CSaleBasket())->GetList([], ['ORDER_ID' => $fieldsRef['ORDER_ID']])));
            try {
                $deliveryName = Services\Manager::getObjectById($order->getField('DELIVERY_ID'))->getName();
            } catch (\Exception $e) {
                $deliveryName = '[неизвестно]';
                App::getInstance()->withRaven(function (\Raven_Client $raven) use ($e) { $raven->captureException($e); });
            }
            $fields = [
                "HOLIDAY" => $holidayText,
                "ORDER_LIST" => Email::orderListStr($items),
                "DELIVERY_PRICE" => SaleFormatCurrency($fieldsRef['DELIVERY_PRICE'], Product::CURRENCY),
                'ORDER_PRICE' => SaleFormatCurrency($order->getPrice(), Product::CURRENCY),
                'DELIVERY_NAME' => $deliveryName,
                'PAY_SYSTEM_NAME' => $payment->getPaymentSystemName() // TODO PSA_NAME?
            ];
            // merge
            foreach ($fields as $k => $v) {
                $fieldsRef[$k] = $v;
            }
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

    // TODO refactor
    private static function mutateUserInfo(&$fieldsRef) {
        if (_::get($_SESSION, 'lastAddedUser')) {
            // TODO make sure it matches order's user
            $user = $_SESSION['lastAddedUser'];
            $lines = [
                '<strong>Ваш аккаунт:</strong>',
                'Логин: '.$user['LOGIN'],
                'Пароль: '.$user['PASSWORD']
                // TODO security: tell the user to change their password asap
            ];
            $fieldsRef['USER_INFO'] = '<br>'.join('<br>', $lines).'<br>';
        }
        return $fieldsRef;
    }

    static function onOrderNewSendEmail($id, $eventName, &$fieldsRef) {
        // mutate fields
        return self::mutateUserInfo($fieldsRef);
    }

    static function onSaleComponentOrderOneStepComplete($orderId) {
        // save full name as an order prop (not the best implementation)
        // используется, например, в квитанциях сбербанка
        $order = sale\Order::load($orderId);
        $arFields = $order->getFieldValues();
        if ($arFields['ID'] > 0 && $arFields['USER_ID'] > 0) {
            $arFIO = array();
            $rsProp = \CSaleOrderPropsValue::GetList(array(), array('ORDER_ID' => $arFields['ID']));
            while ($arProp = $rsProp->Fetch()) {
                if ($arProp['CODE'] == 'IMYA')
                    $arFIO['NAME'] = $arProp['VALUE'];
                elseif ($arProp['CODE'] == 'FAM')
                    $arFIO['LAST_NAME'] = $arProp['VALUE'];
                elseif ($arProp['CODE'] == 'OTCHESTVO')
                    $arFIO['SECOND_NAME'] = $arProp['VALUE'];
            }
            $FIO = trim($arFIO['LAST_NAME'] . ' ' . $arFIO['NAME'] . ' ' . $arFIO['SECOND_NAME']);
            if ($FIO != '') {
                $res = \CSaleOrderProps::GetList(array(), array(
                    'CODE' => 'F_FIO',
                    'PERSON_TYPE_ID' => $arFields['PERSON_TYPE_ID']
                ));
                if ($arProp = $res->Fetch()) {
                    $result = \CSaleOrderPropsValue::Add(array(
                        'ORDER_ID' => $arFields['ID'],
                        'ORDER_PROPS_ID' => $arProp['ID'],
                        'NAME' => $arProp['NAME'],
                        'CODE' => 'F_FIO',
                        'VALUE' => $FIO,
                    ));
                    App::getInstance()->assert($result);
                }
            }
        }
    }

    static function onBeforeUserLogin(&$fieldsRef) {
        // email as login
        $byLogin = CUser::GetList($by = 'ID', $order = 'ASC', ['=LOGIN' => $fieldsRef['LOGIN']])->GetNext();
        $byEmail = CUser::GetList($by = 'ID', $order = 'ASC', ['=EMAIL' => $fieldsRef['LOGIN']])->GetNext();
        if (!$byLogin && $byEmail) {
            $fieldsRef['LOGIN'] = $byEmail['LOGIN'];
        }
        return $fieldsRef;
    }

    static function onBeforeUserRegister(&$fieldsRef) {
        global $APPLICATION;
        if (!isset($fieldsRef['LOGIN']) || $fieldsRef['LOGIN'] === Auth::LOGIN_EQ_EMAIL) {
            // email as login
            $fieldsRef['LOGIN'] = $fieldsRef['EMAIL'];
        }
        if ($fieldsRef['EMAIL']) {
            $emailExists = UserTable::getCount(['EMAIL' => $fieldsRef['EMAIL']]) > 0;
            if ($emailExists) {
                // TODO there is `main` module option that does the same (ensures email uniqueness)
                $APPLICATION->ThrowException('Аккаунт с таким e-mail адресом уже существует');
                return false;
            }
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

    static function onBeforeUserAdd($fields) {
        $_SESSION['lastAddedUser'] = $fields;
        return $fields;
    }

    static function onAfterUserAdd($fields) {
        foreach (self::$deferredUntilAfterUserAdd as $f) {
            $f($fields);
        }
        self::$deferredUntilAfterUserAdd = [];
        return $fields;
    }

    static function onAfterUserRegister(&$arFields) {
        if ($arFields['USER_ID'] > 0) {
            $isPartner = self::isPartnerSignup();
            if ($isPartner) {
                Session::addFlash(['type' => 'success', 'text' => Auth::partnerConfirmationPendingMsg()]);
                CUser::AppendUserGroup($arFields['USER_ID'], [Auth::unconfirmedPartnerId()]);

                // see `onBeforeEventSend` and `NEW_USER` event
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
            if ($isPartner && $holiday['isEnabled']) {
                $holidayText = "Ваша заявка на регистрацию будет рассмотрена <strong>" . $holiday['to'] . "</strong><br><br>";
            }
            $toSend = array(
                'EMAIL' => $arFields['EMAIL'],
                // TODO bug: `$arFields['LOGIN'] === Auth::LOGIN_EQ_EMAIL` even when login is changed in `onBeforeUserRegister`
                // email as login
                'LOGIN' => $arFields['EMAIL'],
                'PASSWORD' => $arFields['CONFIRM_PASSWORD'], // mailing plain text passwords is a bad security practice
                'HOLIDAY' => $holidayText,
                'FULL_NAME' => User::formatFullName($arFields['LAST_NAME'], $arFields['NAME'], $arFields['SECOND_NAME'])
            );
            CEvent::Send("NEW_USER2", App::SITE_ID, $toSend, 'Y');
        }
        return $arFields;
    }
    
    private static function isPartnerSignup() {
        return _::get($_REQUEST, 'user_type') === View::PARTNER_SIGNUP_TYPE;
    }
}