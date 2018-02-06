<?php

use Bitrix\Main\Application;
use Phinx\Migration\AbstractMigration;

class CreateUnconfirmedPartnerGroup extends AbstractMigration {
    function up() {
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            $group = new CGroup();
            $res = $group->Add([
                'ACTIVE' => 'Y',
                'C_SORT' => '100',
                'NAME' => 'Неподтвержденный партнер',
                'DESCRIPTION' => '',
                'STRING_ID' => 'unconfirmed_partner',
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
