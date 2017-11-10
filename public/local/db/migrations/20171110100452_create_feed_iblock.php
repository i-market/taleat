<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Phinx\Migration\AbstractMigration;

class CreateFeedIblock extends AbstractMigration {
    static $data = [
        'NAME' => 'Полезная информация',
        'CODE' => 'feed',
        'TYPE' => 'partner',
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

                'GROUP_ID' => ['9' => 'R'], // partners
                'INDEX_ELEMENT' => 'Y',
                'INDEX_SECTION' => 'N',

                'VERSION' => 1,
                'SITE_ID' => ['s1']
            ]);
            if ($iblockId === false) {
                throw new \Exception($iblock->LAST_ERROR);
            }
            $prop = new CIBlockProperty();
            $propRes = $prop->Add([
                'NAME' => 'Бренд',
                'CODE' => 'BRAND',
                'PROPERTY_TYPE' => 'E',
                'LINK_IBLOCK_ID' => 12, // brands
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