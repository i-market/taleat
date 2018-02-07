<?php

use Bitrix\Main\Application;
use Phinx\Migration\AbstractMigration;

class CreateFullBrandAccessGroup extends AbstractMigration {
    function up() {
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            $group = new CGroup();
            $groupId = $group->Add([
                'ACTIVE' => 'Y',
                'C_SORT' => '100',
                'NAME' => 'Полный доступ ко всем брендам',
                'DESCRIPTION' => 'Для старых аккаунтов оставляем полный доступ ко всем брендам',
                'STRING_ID' => 'full_brand_access',
                'SECURITY_POLICY' => 'a:0:{}',
            ]);
            if (!$groupId) {
                throw new Exception($group->LAST_ERROR);
            }
            // grandfather in everyone
            $partner = 9;
            $users = CUser::GetList($by = 'ID', $order = 'ASC', ['GROUPS_ID' => [$partner]]);
            while ($user = $users->Fetch()) {
                CUser::AppendUserGroup($user['ID'], [$groupId]); // no return value to check. thanks, bitrix.
            }
            $conn->commitTransaction();
        } catch (Exception $e) {
            $conn->rollbackTransaction();
            throw $e;
        }
    }

    function down() {}
}
