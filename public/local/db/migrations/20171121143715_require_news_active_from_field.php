<?php

use Bitrix\Main\Application;
use Phinx\Migration\AbstractMigration;

class RequireNewsActiveFromField extends AbstractMigration {
    static $iblockId = 1; // news

    function up() {
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            $fields = CIBlock::GetFields(self::$iblockId);
            $fields['ACTIVE_FROM']['IS_REQUIRED'] = 'Y';
            $fields['ACTIVE_FROM']['DEFAULT_VALUE'] = '=now';
            CIBlock::SetFields(self::$iblockId, $fields);
            $conn->commitTransaction();
        } catch (Exception $e) {
            $conn->rollbackTransaction();
            throw $e;
        }
    }

    function down() {
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            $fields = CIBlock::GetFields(self::$iblockId);
            $fields['ACTIVE_FROM']['IS_REQUIRED'] = 'N';
            $fields['ACTIVE_FROM']['DEFAULT_VALUE'] = '';
            CIBlock::SetFields(self::$iblockId, $fields);
            $conn->commitTransaction();
        } catch (Exception $e) {
            $conn->rollbackTransaction();
            throw $e;
        }
    }
}
