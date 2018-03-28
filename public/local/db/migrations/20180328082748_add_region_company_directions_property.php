<?php

use Bitrix\Main\Application;
use Phinx\Migration\AbstractMigration;

class AddRegionCompanyDirectionsProperty extends AbstractMigration {
    function up() {
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            $iblockId = 7;
            $prop = new CIBlockProperty();
            $result = $prop->Add([
                'NAME' => 'Схема проезда',
                'ACTIVE' => 'Y',
                'IS_REQUIRED' => 'N',
                'SORT' => '500',
                'CODE' => 'DIRECTIONS',
                'PROPERTY_TYPE' => 'F',
                'FILTRABLE' => 'N',
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
