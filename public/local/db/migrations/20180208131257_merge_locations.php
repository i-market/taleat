<?php

use Bitrix\Main\Application;
use Bitrix\Main\DB\Result;
use Bitrix\Main\Loader;
use Bitrix\Sale\Location\LocationTable;
use Phinx\Migration\AbstractMigration;
use Core\Underscore as _;

class MergeLocations extends AbstractMigration {
    function up() {
        Loader::includeModule('sale');
        $gen = function (Result $r) { while ($x = $r->fetch()) { yield $x; } };
        $locations = $gen(LocationTable::getList());
        $deprecate = 6304;
        $fieldsById = iter\reduce(function ($acc, $loc) use ($deprecate) {
            $fields = iter\reduce(function ($acc, $v, $k) use ($deprecate) {
                $canonical = 5167;
                if (in_array($k, ['COUNTRY_ID', 'PARENT_ID']) && $v == $deprecate) {
                    $acc[$k] = $canonical;
                }
                return $acc;
            }, $loc, []);
            $acc[$loc['ID']] = $fields;
            return $acc;
        }, $locations, []);
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            foreach (iter\filter(_::negate('iter\isEmpty'), $fieldsById) as $id => $fields) {
                $res = LocationTable::update($id, $fields);
                if (!$res->isSuccess()) {
                    throw new \Exception(var_export($res->getErrorMessages(), true));
                }
            }
            $res = LocationTable::delete($deprecate);
            if (!$res->isSuccess()) {
                throw new \Exception(var_export($res->getErrorMessages(), true));
            }
        } catch (\Exception $e) {
            $conn->rollbackTransaction();
            throw $e;
        }
    }

    function down() {}
}
