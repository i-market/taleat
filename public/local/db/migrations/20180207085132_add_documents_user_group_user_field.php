<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Phinx\Migration\AbstractMigration;

class AddDocumentsUserGroupUserField extends AbstractMigration {
    function up() {
        global $APPLICATION;
        if (!Loader::includeModule('ram.users')) {
            throw new \Exception("can't include the required module `ram.users`");
        }
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            $iblockId = 26;
            $uf = new CUserTypeEntity();
            $result = $uf->Add([
                'ENTITY_ID' => "IBLOCK_{$iblockId}_SECTION",
                'FIELD_NAME' => 'UF_USER_GROUP',
                'USER_TYPE_ID' => 'user_groups', // `ram.users` type
                'XML_ID' => '',
                'SORT' => '100',
                'MULTIPLE' => NULL,
                'MANDATORY' => 'N',
                'SHOW_FILTER' => 'N',
                'SHOW_IN_LIST' => NULL,
                'EDIT_IN_LIST' => NULL,
                'IS_SEARCHABLE' => NULL,
                'SETTINGS' => NULL,
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Группа пользователей',
                        'en' => 'User group',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => '',
                        'en' => '',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => '',
                        'en' => '',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => '',
                        'en' => '',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => '',
                        'en' => '',
                    ],
            ]);
            if (!$result) {
                throw new \Exception($APPLICATION->LAST_ERROR);
            }
            $conn->commitTransaction();
        } catch (Exception $e) {
            $conn->rollbackTransaction();
            throw $e;
        }
    }

    function down() {}
}
