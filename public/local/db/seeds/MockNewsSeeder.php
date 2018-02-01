<?php

use Bex\Tools\Iblock\IblockTools;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Core\Util;
use Phinx\Seed\AbstractSeed;
use App\Iblock;

class MockNewsSeeder extends AbstractSeed {
    function run() {
        $previewPic = 'images/pic/news/1.jpg';
        $shortText = 'Стиральные машины, пылесосы, обогреватели, кондиционеры, холодильники – далеко не полный перечень устройств, без которых жизнь современного горожанина превратилась бы в настоящий ад.';
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            Loader::includeModule('iblock');
            $iblockId = IblockTools::find(Iblock::NEWS_TYPE, Iblock::NEWS)->id();
            if (!is_numeric($iblockId)) {
                throw new \Exception("cant'f find iblock: {$iblockId}");
            }
            foreach (range(1, 10) as $n) {
                $imgPath = Util::joinPath([$_SERVER['DOCUMENT_ROOT'], 'local/mockup/src', $previewPic]);
                if (!file_exists($imgPath)) {
                    throw new \Exception("file doesn't exist: {$imgPath}");
                }
                $el = new CIBlockElement();
                $result = $el->Add([
                    'IBLOCK_ID' => $iblockId,
                    'NAME' => "Пример новости {$n}",
                    'CODE' => 'primer-'.$n,
                    'ACTIVE_FROM' => ConvertTimeStamp(time(), 'FULL'),
                    'SORT' => 500,
                    'PREVIEW_PICTURE' => CFile::MakeFileArray($imgPath),
                    'PREVIEW_TEXT' => $shortText,
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
