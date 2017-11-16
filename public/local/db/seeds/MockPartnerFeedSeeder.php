<?php

use Bex\Tools\Iblock\IblockTools;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Core\Util;
use Phinx\Seed\AbstractSeed;
use App\Iblock;

class MockPartnerFeedSeeder extends AbstractSeed {
    function run() {
        $sections = ['Braun', "De'Longhi", 'Babyliss'];
        $longText = 'Стиральные машины, пылесосы, обогреватели, кондиционеры, холодильники – далеко не полный перечень устройств, без которых жизнь современного горожанина превратилась бы в настоящий ад. Люди просто отвыкли обходиться без всего этого. Но бытовая техника периодически выходит из строя, и встает вопрос, что с ней делать.  Всем ли доступен ремонт бытовой техники? Давайте об этом поговорим.';
        $shortText = 'Стиральные машины, пылесосы, обогреватели, кондиционеры, холодильники – далеко не полный...';
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            Loader::includeModule('iblock');
            $iblockId = IblockTools::find(Iblock::PARTNER_TYPE, Iblock::FEED)->id();
            if (!is_numeric($iblockId)) {
                throw new \Exception("cant'f find iblock: {$iblockId}");
            }
            foreach ($sections as $name) {
                $sect = new CIBlockSection();
                $sectId = $sect->Add([
                    'IBLOCK_ID' => $iblockId,
                    'NAME' => $name
                ]);
                if (!$sectId) {
                    throw new \Exception($sect->LAST_ERROR);
                }
                foreach (range(1, 10) as $n) {
                    $el = new CIBlockElement();
                    $result = $el->Add([
                        'IBLOCK_ID' => $iblockId,
                        'IBLOCK_SECTION_ID' => $sectId,
                        'NAME' => "Пример {$name} {$n}",
                        'SORT' => $n * 10,
                        'PREVIEW_TEXT' => $shortText,
                        'DETAIL_TEXT' => $longText
                    ]);
                    if (!$result) {
                        throw new \Exception($el->LAST_ERROR);
                    }
                }
            }
            $conn->commitTransaction();
        } catch (Exception $e) {
            $conn->rollbackTransaction();
            throw $e;
        }
    }
}
