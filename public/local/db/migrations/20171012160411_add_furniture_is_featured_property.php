<?php

use Bitrix\Main\Application;
use Phinx\Migration\AbstractMigration;

class AddFurnitureIsFeaturedProperty extends AbstractMigration {
    function up() {
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            $iblockId = 3;
            $prop = new CIBlockProperty();
            $result = $prop->Add([
                'NAME' => 'Актуальный товар',
                'HINT' => 'Добавить товар на главную страницу сайта',
                'ACTIVE' => 'Y',
                'IS_REQUIRED' => 'N',
                'SORT' => '500',
                'CODE' => 'IS_FEATURED',
                'PROPERTY_TYPE' => 'L',
                'LIST_TYPE' => 'C', // checkbox
                'VALUES' => [
                    [
                        'XML_ID' => 'YES',
                        'VALUE' => 'да',
                        'SORT' => '500',
                        'DEF' => 'N' // unchecked by default
                    ]
                ],
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
