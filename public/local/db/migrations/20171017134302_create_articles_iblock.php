<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Phinx\Migration\AbstractMigration;

class CreateArticlesIblock extends AbstractMigration {
    static $data = [
        'NAME' => 'Статьи',
        'CODE' => 'articles',
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
                'INDEX_ELEMENT' => 'Y',
                'INDEX_SECTION' => 'Y',

                'VERSION' => 1,
                'SITE_ID' => ['s1']
            ]);
            if ($iblockId === false) {
                throw new \Exception($iblock->LAST_ERROR);
            }
            $fields = CIBlock::GetFields($iblockId);
            $fields['CODE']['IS_REQUIRED'] = 'Y';
            $fields['CODE']['DEFAULT_VALUE'] = [
                'UNIQUE' => 'Y',
                'TRANSLITERATION' => 'Y',
                // TODO transliteration settings
            ];
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
