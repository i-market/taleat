<?php

use Bitrix\Main\Application;
use Phinx\Migration\AbstractMigration;
use Core\Underscore as _;

class ImportEventTemplates extends AbstractMigration {
    function up() {
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            $events = _::keyBy('ID', json_decode(file_get_contents(__DIR__.'/../resources/events.json'), true));
            $result = CEventMessage::GetList($by = 'ID', $order = 'ASC');
            while ($template = $result->Fetch()) {
                $data = $events[$template['ID']];
                if (!$data) {
                    throw new Exception('missing data for: '.var_export($template, true));
                }
                $em = new CEventMessage();
                $res = $em->Update($template['ID'], [
                    'EMAIL_FROM' => $data['EMAIL_FROM'],
                    'EMAIL_TO' => $data['EMAIL_TO'],
                    'SUBJECT' => $data['SUBJECT'],
                    'MESSAGE' => $data['MESSAGE']
                ]);
                if (!$res) {
                    throw new Exception("can't update: ".$em->LAST_ERROR);
                }
            }
            $conn->commitTransaction();
        } catch (Exception $e) {
            $conn->rollbackTransaction();
            throw $e;
        }
    }

    function down() {}
}
