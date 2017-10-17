<?php

use Bex\Tools\Iblock\IblockTools;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Core\Util;
use Phinx\Seed\AbstractSeed;
use App\Iblock;

class ClientsSeeder extends AbstractSeed {
    function run() {
        $items = json_decode(file_get_contents(dirname(__FILE__).'/clients.json'), true);
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            Loader::includeModule('iblock');
            $iblockId = IblockTools::find(Iblock::CONTENT_TYPE, Iblock::CLIENTS)->id();
            if (!is_numeric($iblockId)) {
                throw new \Exception("cant'f find iblock: {$iblockId}");
            }
            foreach ($items as $idx => $item) {
                $imgPath = Util::joinPath([$_SERVER['DOCUMENT_ROOT'], 'local/mockup/src', $item['img']]);
                if (!file_exists($imgPath)) {
                    throw new \Exception("file doesn't exist: {$imgPath}");
                }
                $el = new CIBlockElement();
                $result = $el->Add([
                    'IBLOCK_ID' => $iblockId,
                    'NAME' => $item['name'],
                    'SORT' => ($idx + 1) * 10,
                    'PREVIEW_PICTURE' => CFile::MakeFileArray($imgPath)
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
