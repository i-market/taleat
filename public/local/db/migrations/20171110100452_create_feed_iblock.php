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
            $fields = CIBlock::GetFields($iblockId);
            $fields['ACTIVE_FROM']['IS_REQUIRED'] = 'Y';
            $fields['ACTIVE_FROM']['DEFAULT_VALUE'] = '=now';
            CIBlock::SetFields($iblockId, $fields);
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
