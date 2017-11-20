<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Phinx\Migration\AbstractMigration;

class AddProductAdditionalTextProperty extends AbstractMigration {
    function up() {
        Loader::includeModule('iblock');
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            $iblockId = 3;
            $prop = new CIBlockProperty();
            $result = $prop->Add([
                'NAME' => 'Дополнительный текст',
                'ACTIVE' => 'Y',
                'IS_REQUIRED' => 'N',
                'SORT' => '500',
                'CODE' => 'ADDITIONAL_TEXT',
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE' => 'HTML',
                'FILTRABLE' => 'Y',
                'IBLOCK_ID' => $iblockId,
            ]);
            if (!$result) {
                throw new \Exception($prop->LAST_ERROR);
            }
            $conn->commitTransaction();
        } catch (Exception $e) {
            $conn->rollbackTransaction();
            throw $e;
        }
    }

    function down() {}
}
