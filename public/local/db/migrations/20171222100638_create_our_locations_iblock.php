<?php

use Bitrix\Main\Application;
use Phinx\Migration\AbstractMigration;
use Bitrix\Main\Loader;

class CreateOurLocationsIblock extends AbstractMigration {
    static $data = [
        'NAME' => 'Приемные пункты',
        'CODE' => 'our_locations',
        'TYPE' => 'content',
    ];

    function up() {
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            Loader::includeModule('iblock');
            $iblock = new CIBlock();
            $res = $iblock->GetList([], ['CODE' => static::$data['CODE']]);
            if ($res->Fetch()) {
                return;
            }
            $iblockId = $iblock->Add([
                'NAME' => static::$data['NAME'],
                'CODE' => static::$data['CODE'],
                'IBLOCK_TYPE_ID' => static::$data['TYPE'],

                'GROUP_ID' => ['2' => 'R'],
                'INDEX_ELEMENT' => 'N',
                'INDEX_SECTION' => 'N',

                'VERSION' => 1,
                'SITE_ID' => ['s1']
            ]);
            if ($iblockId === false) {
                throw new \Exception($iblock->LAST_ERROR);
            }
            $prop = new CIBlockProperty();
            $propRes = $prop->Add([
                'NAME' => 'Координаты (широта, долгота)',
                'HINT' => 'Например "55.753215, 37.622504"',
                'CODE' => 'LAT_LONG',
                'PROPERTY_TYPE' => 'S',
                'IS_REQUIRED' => 'Y',
                'IBLOCK_ID' => $iblockId,
                'SORT' => '500',
                'FILTRABLE' => 'Y',
                'ACTIVE' => 'Y'
            ]);
            if (!$propRes) {
                throw new \Exception($prop->LAST_ERROR);
            }
            $prop = new CIBlockProperty();
            $propRes = $prop->Add([
                'NAME' => 'Описание',
                'CODE' => 'DESCRIPTION',
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE' => 'HTML',
                'IS_REQUIRED' => 'N',
                'IBLOCK_ID' => $iblockId,
                'SORT' => '500',
                'FILTRABLE' => 'Y',
                'ACTIVE' => 'Y'
            ]);
            if (!$propRes) {
                throw new \Exception($prop->LAST_ERROR);
            }
            $prop = new CIBlockProperty();
            $propRes = $prop->Add([
                'NAME' => 'Схема проезда',
                'CODE' => 'DIRECTIONS',
                'PROPERTY_TYPE' => 'F',
                'IS_REQUIRED' => 'N',
                'IBLOCK_ID' => $iblockId,
                'SORT' => '500',
                'FILTRABLE' => 'Y',
                'ACTIVE' => 'Y'
            ]);
            if (!$propRes) {
                throw new \Exception($prop->LAST_ERROR);
            }
            $conn->commitTransaction();
        } catch (Exception $e) {
            $conn->rollbackTransaction();
            throw $e;
        }
    }

    function down() {
        Loader::includeModule('iblock');
        $iblock = new CIBlock();
        $res = $iblock->GetList([], ['CODE' => static::$data['CODE']]);
        if ($ib = $res->Fetch()) {
            CIBlock::Delete($ib['ID']);
        }
    }
}
