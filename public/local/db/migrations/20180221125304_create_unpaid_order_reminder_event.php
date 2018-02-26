<?php

use Bitrix\Main\Application;
use Phinx\Migration\AbstractMigration;

class CreateUnpaidOrderReminderEvent extends AbstractMigration {
    static $eventData = [
        'EVENT_NAME' => 'UNPAID_ORDER_REMINDER',
        'EVENT_TITLE' => 'У вас имеется неоплаченный заказ',
        'MESSAGE_TITLE' => '#SITE_NAME#: У вас имеется неоплаченный заказ',
        'FIELDS' => [
            'FULL_NAME' => 'ФИО',
            'ORDER_LIST' => 'Состав заказа',
            'DELIVERY_PRICE' => 'Стоимость доставки',
            'PRICE' => 'Общая сумма',
            'PAY_ACTION' => 'Текст про оплату'
        ]
    ];

    function up() {
        $connection = Application::getConnection();
        $connection->startTransaction();
        $description = join("\n", array_map(function($key) {
            $label = static::$eventData['FIELDS'][$key];
            return "#{$key}# - {$label}";
        }, array_keys(static::$eventData['FIELDS'])));
        $message = <<<HTML
Здравствуйте, #FULL_NAME#!<br>
Заказанный Вами товар есть в наличии, заказ сформирован.<br>
Вы заказали: <br>
#ORDER_LIST#<br>
Стоимость доставки – #DELIVERY_PRICE#<br>
Работаем только по предоплате. Отправка заказа после поступления оплаты на расчетный счет.<br>
Общая сумма к оплате – #PRICE#<br>
<br>
Оплатить заказ Вы можете личном кабинете в разделе "Мои заказы".<br>
#PAY_ACTION#<br>
Заказ будет активен в течении 2 банковских дней.<br>
HTML;
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
            'EMAIL_FROM' => '#SALE_EMAIL#',
            'EMAIL_TO' => '#EMAIL#',
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
