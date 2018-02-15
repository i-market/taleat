<?php

use Bitrix\Main\Application;
use Phinx\Migration\AbstractMigration;

class CreateTehZaklUpdateEvent extends AbstractMigration {
    static $eventData = [
        'EVENT_NAME' => 'TEH_ZAKL_UPDATE',
        'EVENT_TITLE' => 'Статус технического заключения изменен',
        'MESSAGE_TITLE' => '#SITE_NAME#: Статус технического заключения изменен',
        'FIELDS' => [
            'NUMBER' => 'Номер технического заключения',
            'STATUS' => 'Статус',
            'COMMENT' => 'Комментарий'
        ]
    ];

    function up() {
        $connection = Application::getConnection();
        $connection->startTransaction();
        $description = join("\n", array_map(function($key) {
            $label = static::$eventData['FIELDS'][$key];
            return "#{$key}# - {$label}";
        }, array_keys(static::$eventData['FIELDS'])));
        $message = "Статус технического заключения #NUMBER# изменен на: <strong>#STATUS#</strong>"
            . "<br />\n<br />\n"
            . "#COMMENT#";
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
