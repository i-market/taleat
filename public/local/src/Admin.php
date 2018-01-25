<?php

namespace App;

use Bex\Tools\Iblock\IblockTools;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use CCatalogGroup;
use CIBlockElement;
use CPrice;
use iter;
use Core\Underscore as _;
use App\View as v;

Loader::includeModule('iblock');
Loader::includeModule('catalog');

class Admin {
    static function dispatch($params) {
        // a sprinkle of magic: dispatch by naming convention
        $action = [self::class, 'action_'._::get($params, 'action', 'index')];
        if (is_callable($action)) {
            return $action();
        } else {
            App::getInstance()->assert(false, 'unknown action: '.$params['action']);
            return self::action_index();
        }
    }

    static function action_index() {
        return v::render('partials/admin/action_index.php');
    }

    static function action_export() {
        global $APPLICATION;
        try {
            /** @var \PHPExcel_Writer_IWriter $writer */
            $writer = self::catalogExport()['writer'];
            $filename = 'catalog-' . date('Y-m-d') . '.xls';
            header('Content-Description: File Transfer');
            header('Cache-Control: public, must-revalidate, max-age=0');
            header('Pragma: public');
            header('Content-Transfer-Encoding: binary');
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            $APPLICATION->RestartBuffer();
            $writer->save('php://output');
            die();
        } catch (\Exception $e) {
            App::getInstance()->withRaven(function (\Raven_Client $raven) use ($e) {
                return $raven->captureException($e);
            });
            return 'error'; // TODO error handling
        }
    }

    static function catalogExport() {
        require_once $_SERVER["DOCUMENT_ROOT"].'/local/legacy/phpexcel/PHPExcel/IOFactory.php';

        $baseGroup = CCatalogGroup::GetBaseGroup();
        $prices = Iblock::iter(CPrice::GetList([], ['CATALOG_GROUP_ID' => $baseGroup['ID']]));
        $pricesByProductId = _::group(iter\toArray($prices), 'PRODUCT_ID');
        $elements = Iblock::iter(CIBlockElement::GetList(['ID' => 'ASC'], [
            'IBLOCK_ID' => IblockTools::find(Iblock::CATALOG_TYPE, Iblock::FURNITURE)->id()
        ], false, false, ['ID', 'IBLOCK_ID', 'PROPERTY_ARTNUMBER', 'NAME']));
        $products = iter\map(function ($elem) use ($pricesByProductId) {
            $prices = $pricesByProductId[$elem['ID']];
            if (count($prices) !== 1) {
                throw new \Exception('product should have a single base price. offending product id: '.$elem['ID']);
            }
            return array_merge($elem, [
                'BASE_PRICE' => _::first($prices)
            ]);
        }, $elements);
        $rows = iter\map(function ($p) {
            return [$p['ID'], $p['~PROPERTY_ARTNUMBER_VALUE'], $p['BASE_PRICE']['PRICE'], $p['~NAME']];
        }, $products);

        $header = ['ID', 'Артикул', 'Цена', 'Название'];
        App::getInstance()->assert(count($header) === count($rows->current()), 'column count mismatch');
        $book = new \PHPExcel();
        $sheet = $book->getActiveSheet();
        $sheet->setTitle('Каталог');
        $cols = range('A', 'Z');
        foreach (iter\zip($cols, $header) as list($col, $val)) {
            $sheet->setCellValue($col.'1', $val);
        }
        foreach (iter\zip(iter\range(2, INF), $rows) as list($row, $cells)) {
            foreach (iter\zip($cols, $cells) as list($col, $val)) {
                $sheet->setCellValue($col.strval($row), $val);
            }
        }

        $writer = \PHPExcel_IOFactory::createWriter($book, 'Excel5');
        return ['book' => $book, 'writer' => $writer];
    }
}
