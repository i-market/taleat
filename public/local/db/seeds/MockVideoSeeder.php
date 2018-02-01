<?php

use Bex\Tools\Iblock\IblockTools;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Phinx\Seed\AbstractSeed;
use App\Iblock;

class MockVideoSeeder extends AbstractSeed {
    function run() {
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            Loader::includeModule('iblock');
            $iblockId = IblockTools::find(Iblock::CONTENT_TYPE, Iblock::VIDEOS)->id();
            if (!is_numeric($iblockId)) {
                throw new \Exception("cant'f find iblock: {$iblockId}");
            }
            foreach (range(1, 15) as $n) {
                $el = new CIBlockElement();
                $result = $el->Add([
                    'IBLOCK_ID' => $iblockId,
                    'NAME' => "Пример видео {$n}",
                    'SORT' => 500,
                    'PROPERTY_VALUES' => [
                        'URL' => 'https://www.youtube.com/embed/hj7ZYjVFDGI?rel=0'
                    ]
                ]);
                if (!$result) {
                    throw new \Exception($el->LAST_ERROR);
                }
            }
            $conn->commitTransaction();
        } catch (Exception $e) {
            $conn->rollbackTransaction();
            throw $e;
        }
    }
}
