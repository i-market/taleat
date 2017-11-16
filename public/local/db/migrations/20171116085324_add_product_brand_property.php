<?php

use Bitrix\Main\Application;
use Phinx\Migration\AbstractMigration;

class AddProductBrandProperty extends AbstractMigration {
    function up() {
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            $conn->commitTransaction();
        } catch (Exception $e) {
            $conn->rollbackTransaction();
            throw $e;
        }
    }

    function down() {}
}
