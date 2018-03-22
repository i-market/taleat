<?php

namespace App;

use App\View as v;
use CSubscription;
use Bitrix\Main\Loader;
use Core\Underscore as _;
use iter;

Loader::includeModule('subscribe');

class Email {
    static function orderListStr($items) {
        $ret = '';
        foreach (iter\values($items) as $idx => $item) {
            $qty = strval($item['QUANTITY'] + 0); // strip trailing zeros
            $ret .= ($idx + 1).'. '.$item['NAME'].($item['QUANTITY'] > 0 ? ' x '.$qty : '');
            $ret .= "\n<br />";
        }
        return $ret;
    }

    static function newsletterContext() {
        $sub = CSubscription::GetUserSubscription();
        if ($sub['ID'] === 0) {
            $sub = null;
        }
        $rubrics = $sub
            ? CSubscription::GetRubricArray($sub['ID']) // subscribed-to rubrics
            : [];
        if (!$sub) {
            $state = 'no_subscription';
        } elseif (_::get($sub, 'CONFIRMED') === 'N') {
            $state = 'awaiting_confirmation';
        } elseif (in_array(App::NEWSLETTER_ID, $rubrics)) {
            $state = 'rubric_checked';
        } else {
            $state = 'rubric_not_checked';
        }
        return compact('sub', 'rubrics', 'state');
    }

    static function showNewsletterSub() {
        global $USER;
        App::getInstance()->assert($USER->IsAuthorized());
        $params = $_POST;
        $ctx = self::newsletterContext();
        $errors = [];
        $action = _::get($params, 'action');
        if ($action) {
            try {
                $fieldsBase = [
                    'ACTIVE' => 'Y',
                    'CONFIRMED' => 'Y', // no confirmation required
                    'SEND_CONFIRM' => 'N',
                    'USER_ID' => $USER->GetID(),
                    'FORMAT' => App::NEWSLETTER_FORMAT,
                    'EMAIL' => $USER->GetEmail(),
                    'RUB_ID' => [App::NEWSLETTER_ID],
                ];
                switch ($action) {
                    case 'subscribe':
                        $s = new CSubscription();
                        $res = $s->Add($fieldsBase);
                        if (!$res) {
                            $errors[] = $s->LAST_ERROR;
                        }
                        break;
                    case 'confirm':
                        // TODO confirmation might be broken. clean up.
                        $s = new CSubscription();
                        $res = $s->Update($ctx['sub']['ID'], array_merge($fieldsBase, [
                            'CONFIRM_CODE' => $params['CONFIRM_CODE']
                        ]));
                        if (!$res) {
                            $errors[] = $s->LAST_ERROR;
                        }
                        break;
                    case 'check_rubric':
                        $s = new CSubscription();
                        $res = $s->Update($ctx['sub']['ID'], _::update($fieldsBase, 'RUB_ID', function ($ids) use ($ctx) {
                            return array_unique(array_merge($ctx['rubrics'], $ids));
                        }));
                        if (!$res) {
                            $errors[] = $s->LAST_ERROR;
                        }
                        break;
                    case 'uncheck_rubric':
                        $s = new CSubscription();
                        $res = $s->Update($ctx['sub']['ID'], array_merge($fieldsBase, [
                            'RUB_ID' => array_diff($ctx['rubrics'], [App::NEWSLETTER_ID])
                        ]));
                        if (!$res) {
                            $errors[] = $s->LAST_ERROR;
                        }
                        break;
                    default:
                        App::getInstance()->assert(false, 'unknown action');
                        break;
                }
                // mutate: refresh after db changes
                $ctx = self::newsletterContext();
            } catch (\Exception $e) {
                $errors = [v::genericErrorMessageHtml()];
                App::getInstance()->withRaven(function (\Raven_Client $raven) use ($e) {
                    $raven->captureException($e);
                });
            }
        }
        echo v::render('partials/newsletter_sub.php', array_merge($ctx, [
            'errors' => $errors
        ]));
    }
}