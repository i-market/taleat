<?php

use Bitrix\Main\Application;
use Phinx\Migration\AbstractMigration;

class CreateNewUnconfirmedPartnerEvent extends AbstractMigration {
    static $eventData = [
        'EVENT_NAME' => 'NEW_UNCONFIRMED_PARTNER',
        'EVENT_TITLE' => 'Зарегистрировался новый сервисный центр',
        'MESSAGE_TITLE' => '#SITE_NAME#: Зарегистрировался новый сервисный центр',
        'FIELDS' => [
            'USER_ID' => 'ID пользователя',
            'EMAIL' => 'Email',
            'WORK_COMPANY' => 'Компания'
        ]
    ];

    function up() {
        $connection = Application::getConnection();
        $connection->startTransaction();
        $description = join("\n", array_map(function($key) {
            $label = static::$eventData['FIELDS'][$key];
            return "#{$key}# - {$label}";
        }, array_keys(static::$eventData['FIELDS'])));
        $link = '<a href="http://#SERVER_NAME#/bitrix/admin/user_edit.php?ID=#USER_ID#">Редактировать пользователя</a>';
        $fields = join("<br />\n", array_map(function($key) {
            $label = static::$eventData['FIELDS'][$key];
            return "{$label}: #{$key}#";
        }, array_keys(static::$eventData['FIELDS'])));
        $message = "{$fields}<br />\n<br />\n{$link}";
        CEventType::Add([
            'EVENT_NAME' => static::$eventData['EVENT_NAME'],
            'NAME' => static::$eventData['EVENT_TITLE'],
            'LID' => 'ru',
            'DESCRIPTION' => $description,
        ]);
        $cEventMessage = new CEventMessage();
        $addResult = $cEventMessage->Add([
            'ACTIVE' => 'Y',
            'EVENT_NAME' => static::$eventData['EVENT_NAME'],
            'LID' => ['s1'],
            'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
            'EMAIL_TO' => '#EMAIL_TO#',
            'BCC' => '',
            'SUBJECT' => static::$eventData['MESSAGE_TITLE'],
            'BODY_TYPE' => 'html',
            'SITE_TEMPLATE_ID' => 'letter',
            'MESSAGE' => $message,
        ]);
        if (false === $addResult) {
            $connection->rollbackTransaction();
            throw new Exception('Failed to add EventMessage: '.$cEventMessage->LAST_ERROR);
        }
        $connection->commitTransaction();
    }

    function down() {
        $connection = Application::getConnection();
        $connection->startTransaction();
        CEventType::Delete(static::$eventData['EVENT_NAME']);
        $dbEventMessages = \Bitrix\Main\Mail\Internal\EventMessageTable::getList([
            'filter' => ['EVENT_NAME' => static::$eventData['EVENT_NAME']],
            'select' => ['ID'],
        ]);
        while ($eventMessage = $dbEventMessages->fetch()) {
            if (!CEventMessage::Delete(intval($eventMessage['ID']))) {
                $connection->rollbackTransaction();
                throw new Exception('Failed to delete EventMessage');
            }
        }
        $connection->commitTransaction();
    }
}
