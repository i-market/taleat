<?php

use Bitrix\Main\Application;
use Phinx\Migration\AbstractMigration;

class SetEmailTemplate extends AbstractMigration {
    function up() {
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            $result = CEventMessage::GetList($by = 'ID', $order = 'ASC');
            while ($template = $result->Fetch()) {
                $msg = $this->unwrap($template);
                $em = new CEventMessage();
                $res = $em->Update($template['ID'], [
                    'SITE_TEMPLATE_ID' => 'letter',
                    'MESSAGE' => $template['BODY_TYPE'] === 'text' ? TxtToHTML($msg) : $msg,
                    'BODY_TYPE' => 'html'
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

    function unwrap($template) {
        $match = function ($str, $pattern) {
            $matchesRef = [];
            preg_match($pattern, $str, $matchesRef);
            list($m) = $matchesRef;
            return [$m, mb_strpos($str, $m)];
        };
        $html = $template['MESSAGE'];
        if (mb_strpos($html, '<div') === 0) {
            list($start, $startPos) = $match($html, '/<p style="font-size: 15px; padding: 10px 0;">[\n\r]*/');
            list($_, $endPos) = $match($html, "/^\s+<\/p>[\n\r]+\s+<\/div>/m");
        } elseif ($match($html, '/^\s*<!DOCTYPE/')[1] === 0) {
            list($start, $startPos) = $match($html, '/<body>[\n\r]*/');
            list($_, $endPos) = $match($html, "/<\/body>/");
        } else {
            return $html;
        }
        if (!$startPos || !$endPos) {
            throw new \Exception('unexpected template html, id: ' . $template['ID']);
        }
        $msgStart = $startPos + mb_strlen($start);
        return mb_substr($html, $msgStart, $endPos - $msgStart);
    }
}
