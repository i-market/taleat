<?php

use Bitrix\Main\Application;
use Phinx\Migration\AbstractMigration;
use Core\Util;
use Core\Underscore as _;

class AddBrandImages extends AbstractMigration {
    function up() {
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            $iblockId = 3; // catalog `furniture`
            $images = [
                'moser'	=> 'moser.png',
                'delonghi' => 'de-longhi.png',
                'kenwood' => 'kenwood.png',
                // babyliss
                'katalog1' => 'babyliss.png',
                'braun' => 'braun.png'
            ];
            foreach ($images as $code => $filename) {
                $sect = CIBlockSection::GetList([], ['IBLOCK_ID' => $iblockId, 'CODE' => $code])->GetNext();
                if (!$sect) {
                    throw new Exception("can't find section `{$code}`");
                }
                $path = Util::joinPath([$_SERVER['DOCUMENT_ROOT'], 'local/db/resources/brand-images', $filename]);
                $s = new CIBlockSection();
                $result = $s->Update($sect['ID'], [
                    'PICTURE' => CFile::MakeFileArray($path)
                ]);
                if (!$result) {
                    throw new Exception($s->LAST_ERROR);
                }
            }
            $conn->commitTransaction();
        } catch (Exception $e) {
            $conn->rollbackTransaction();
            throw $e;
        }
    }

    function down() {}
}
