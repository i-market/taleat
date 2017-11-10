<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Phinx\Migration\AbstractMigration;

class CreatePartnerIblockType extends AbstractMigration {
    function up() {
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            Loader::includeModule('iblock');
            $ibType = new CIBlockType();
            $fields = array (
                'ID' => 'partner',
                'EDIT_FILE_BEFORE' => '',
                'EDIT_FILE_AFTER' => '',
                'IN_RSS' => NULL,
                'SECTIONS' => 'Y',
                'SORT' => '500',
                'LANG' =>
                    array (
                        'ru' =>
                            array (
                                'NAME' => 'Партнерам',
                                'SECTION_NAME' => '',
                                'ELEMENT_NAME' => '',
                            ),
                        'en' =>
                            array (
                                'NAME' => 'For partners',
                                'SECTION_NAME' => '',
                                'ELEMENT_NAME' => '',
                            ),
                    ),
            );
            $result = $ibType->Add($fields);
            if (!$result) {
                throw new \Exception($ibType->LAST_ERROR);
            }
            $conn->commitTransaction();
        } catch (Exception $e) {
            $conn->rollbackTransaction();
            throw $e;
        }
    }

    function down() {}
}
