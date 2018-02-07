<?php

use Bitrix\Main\Application;
use Phinx\Migration\AbstractMigration;

class CreateBabylissUserGroup extends AbstractMigration {
    function up() {
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            $group = new CGroup();
            $res = $group->Add([
                'ACTIVE' => 'Y',
                'C_SORT' => '100',
                'NAME' => 'Babyliss',
                'DESCRIPTION' => '',
                'STRING_ID' => 'babyliss',
                'SECURITY_POLICY' => 'a:0:{}',
            ]);
            if (!$res) {
                throw new Exception($group->LAST_ERROR);
            }
            $conn->commitTransaction();
        } catch (Exception $e) {
            $conn->rollbackTransaction();
            throw $e;
        }
    }

    function down() {}
}
