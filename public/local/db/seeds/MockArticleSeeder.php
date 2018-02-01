<?php

use Bex\Tools\Iblock\IblockTools;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Core\Util;
use Phinx\Seed\AbstractSeed;
use App\Iblock;

class MockArticleSeeder extends AbstractSeed {
    function run() {
        $longText = 'Стиральные машины, пылесосы, обогреватели, кондиционеры, холодильники – далеко не полный перечень устройств, без которых жизнь современного горожанина превратилась бы в настоящий ад. Люди просто отвыкли обходиться без всего этого. Но бытовая техника периодически выходит из строя, и встает вопрос, что с ней делать.  Всем ли доступен ремонт бытовой техники? Давайте об этом поговорим.';
        $shortText = 'Стиральные машины, пылесосы, обогреватели, кондиционеры, холодильники – далеко не полный...';
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            Loader::includeModule('iblock');
            $iblockId = IblockTools::find(Iblock::CONTENT_TYPE, Iblock::ARTICLES)->id();
            if (!is_numeric($iblockId)) {
                throw new \Exception("cant'f find iblock: {$iblockId}");
            }
            foreach (range(1, 10) as $n) {
                $hasImg = $n <= 3;
                $imgPath = Util::joinPath([$_SERVER['DOCUMENT_ROOT'], 'local/mockup/src/images/pic/pic-4.jpg']);
                if (!file_exists($imgPath)) {
                    throw new \Exception("file doesn't exist: {$imgPath}");
                }
                $el = new CIBlockElement();
                $result = $el->Add([
                    'IBLOCK_ID' => $iblockId,
                    'NAME' => "Пример статьи {$n}",
                    'CODE' => 'primer-'.$n,
                    'SORT' => 500,
                    'PREVIEW_PICTURE' => $hasImg ? CFile::MakeFileArray($imgPath) : null,
                    'PREVIEW_TEXT' => $hasImg ? $longText : $shortText,
                    'DETAIL_TEXT' => file_get_contents(Util::joinPath([__DIR__, 'mock_post.html'])),
                    'DETAIL_TEXT_TYPE' => 'html'
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
