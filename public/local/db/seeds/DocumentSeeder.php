<?php

use Bex\Tools\Iblock\IblockTools;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Core\Util;
use Phinx\Seed\AbstractSeed;
use App\Iblock;

class DocumentSeeder extends AbstractSeed {
    function run() {
        $data = [
            'Braun' => [
                ['link' => '/Braun_Catalog_2016.pdf', 'text' => 'Каталог продукции Braun 2016 г.'],
                ['link' => '/partneram/BRAUN_shaver.pdf', 'text' => 'Таблица соответствия сеток Braun'],
                ['link' => '/DOG_BR_Tal2016.doc', 'text' => 'Договор'],
                ['link' => '/Anketa_RSC_2015.doc', 'text' => 'Анкета'],
                ['link' => '/Formular_ICS online (7).doc', 'text' => 'Формуляр ICS-Online'],
                ['link' => '/partneram/RSC_RUR_Krasota_01_08_16.xls', 'text' => 'Прайс-лист'],
                ['link' => '/garantiya_podarki.pdf', 'text' => 'Гарантия на подарки Braun'],
            ],
            "De'Longhi" => [
                ['link' => '/postavka_kuhnya_2016.doc', 'text' => 'Договор'],
                ['link' => '/Anketa_RSC_2015.doc', 'text' => 'Анкета'],
                ['link' => '/partneram/Kuhnya_080217.xls', 'text' => 'Прайс-лист'],
                ['link' => '/partneram/braun/doc/pismo kuhnia.pdf', 'text' => 'Письмо о расшифровке серийного номера (даты производства)'],
            ],
            'Babyliss' => [
                ['link' => '/partneram/babyliss/doc/DOG_Babylss_2015.doc', 'text' => 'Договор сервисного обслуживания'],
                ['link' => '/partneram/braun/doc/dop_soglashenie_1_Babyliss.doc', 'text' => 'Дополнительное соглашение'],
                ['link' => '/partneram/zamena 21.03.2017.xls', 'text' => 'Список моделей, подлежащих сервисному обслуживанию, заменам (все) и возможному ремонту без з/ч'],
                ['link' => '/partneram/List of Accessories 20.10.2016.xls', 'text' => 'Прайс-лист аксессуаров Babyliss'],
                ['link' => '/partneram/babyliss/doc/ZAYVKA.xls', 'text' => 'Проформа заявки обменного фонда'],
                ['link' => '/partneram/babyliss/doc/pismo1105.pdf', 'text' => 'Письмо Babyliss о подделках C1000/C1100'],
                ['link' => '/partneram/razbor 17.01.17.7z', 'text' => 'Изделия в разборе'],
                ['link' => '/partneram/List of Accessories 01.02.2016.xls', 'text' => 'Аксессуары Babyliss'],
                ['link' => '/Teh_par.7z', 'text' => 'Технические характеристики'],
            ],
        ];
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            Loader::includeModule('iblock');
            $iblockId = IblockTools::find(Iblock::PARTNER_TYPE, Iblock::DOCUMENTS)->id();
            if (!is_numeric($iblockId)) {
                throw new \Exception("cant'f find iblock: {$iblockId}");
            }
            foreach ($data as $brand => $items) {
                $sect = new CIBlockSection();
                $sectId = $sect->Add([
                    'IBLOCK_ID' => $iblockId,
                    'NAME' => $brand
                ]);
                foreach ($items as $idx => $item) {
                    $path = Util::joinPath([$_SERVER['DOCUMENT_ROOT'], $item['link']]);
                    if (!file_exists($path)) {
                        throw new \Exception("file doesn't exist: {$path}");
                    }
                    $el = new CIBlockElement();
                    $result = $el->Add([
                        'IBLOCK_ID' => $iblockId,
                        'NAME' => $item['text'],
                        'SORT' => 100 + ($idx + 1) * 10,
                        'IBLOCK_SECTION_ID' => $sectId,
                        'PROPERTY_VALUES' => [
                            'DOCUMENT' => CFile::MakeFileArray($path)
                        ]
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
