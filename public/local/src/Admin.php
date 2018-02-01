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
        $action = _::get($params, 'action', 'index');
        // magic: dispatch by naming convention
        $actionFn = [self::class, 'action_'.$action];
        if ($action !== 'index') {
            echo '<p><a href="?action=index">Назад</a></p>'; // TODO admin panel navigation
        }
        try {
            if (is_callable($actionFn)) {
                return $actionFn($params);
            } else {
                App::getInstance()->assert(false, 'unknown action: '.$params['action']);
                return self::action_index();
            }
        } catch (\Exception $e) {
            App::getInstance()->withRaven(function (\Raven_Client $raven) use ($e) {
                return $raven->captureException($e);
            });
            return v::genericErrorMessageHtml();
        }
    }

    static function action_index() {
        return v::render('partials/admin/action_index.php');
    }

    static function action_export() {
        global $APPLICATION;
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
    }

    static function action_import() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_FILES['file'])) {
                App::getInstance()->assert(false, 'illegal state');
                return v::genericErrorMessageHtml();
            }
            $path = $_FILES['file']['tmp_name'];
            $result = self::catalogImport($path);
            return v::render('partials/admin/action_import.php', ['result' => $result]);
        } else {
            return v::render('partials/admin/action_import.php', ['result' => []]);
        }
    }

    static function products() {
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
        return $products;
    }

    static function catalogExport() {
        require_once $_SERVER["DOCUMENT_ROOT"].'/local/legacy/phpexcel/PHPExcel/IOFactory.php';

        $products = self::products();
        $rows = iter\map(function ($p) {
            return [$p['ID'], $p['~PROPERTY_ARTNUMBER_VALUE'], $p['BASE_PRICE']['PRICE'], $p['~NAME']];
        }, $products);

        $header = ['ID', 'Артикул', 'Цена', 'Название'];
        $stringTypeCols = ['B', 'D']; // prevent incorrect text -> number conversion
        App::getInstance()->assert(count($header) === count($rows->current()), 'column count mismatch');
        $book = new \PHPExcel();
        $sheet = $book->setActiveSheetIndex(0);
        $sheet->setTitle('Каталог');
        $cols = range('A', 'Z');
        foreach (iter\zip($cols, $header) as list($col, $val)) {
            $sheet->setCellValue($col.'1', $val);
        }
        foreach (iter\zip(iter\range(2, INF), $rows) as list($row, $cells)) {
            foreach (iter\zip($cols, $cells) as list($col, $val)) {
                if (in_array($col, $stringTypeCols)) {
                    $sheet->setCellValueExplicit($col.strval($row), $val, \PHPExcel_Cell_DataType::TYPE_STRING);
                } else {
                    $sheet->setCellValue($col.strval($row), $val);
                }
            }
        }

        $writer = \PHPExcel_IOFactory::createWriter($book, 'Excel5');
        return ['book' => $book, 'writer' => $writer];
    }

    static function rows($filePath) {
        require_once $_SERVER["DOCUMENT_ROOT"].'/local/legacy/phpexcel/PHPExcel/IOFactory.php';

        $reader = \PHPExcel_IOFactory::createReader(\PHPExcel_IOFactory::identify($filePath));
        /** @var \PHPExcel $book */
        $book = $reader->load($filePath);
        $sheet = $book->getSheet(0);
        $rows = iter\map(function (\PHPExcel_Worksheet_Row $row) {
            $cells = $row->getCellIterator();
            return iter\toArray(iter\map(function (\PHPExcel_Cell $cell) {
                return $cell->getCalculatedValue();
            }, $cells));
        }, iter\drop(1, $sheet->getRowIterator()));
        return $rows;
    }

    static function compare($rows, $products) {
        $productsById = _::keyBy('ID', iter\toArray($products));
        $ret = iter\map(function ($row) use ($productsById) {
            list($id, $_, $price) = $row;
            $product = _::get($productsById, strval($id));
            return [
                'row' => $row,
                'nextPrice' => $price,
                'product' => $product,
                // checking for price change only
                'isChanged' => $product === null
                    ? false // TODO handle deleted products
                    : $price != $product['BASE_PRICE']['PRICE'] // important to use non-strict equality
            ];
        }, $rows);
        return $ret;
    }

    static function catalogImport($filePath) {
        $rows = self::rows($filePath);
        $products = self::products();
        $changed = iter\filter(function ($m) {
            return $m['isChanged'];
        }, self::compare($rows, $products));
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            $log = [];
            $results = iter\toArray(iter\map(function ($m) use (&$log) {
                $result = CPrice::SetBasePrice($m['product']['ID'], $m['nextPrice'], $m['product']['BASE_PRICE']['CURRENCY']);
                if (!$result) {
                    throw new \Exception("can't set the base price");
                }
                $log[] = [time(), 'price change', $m];
                return $result;
            }, $changed));
            App::getInstance()->log(...$log); // log in case we need to revert the changes
            $conn->commitTransaction();
        } catch (\Exception $e) {
            $conn->rollbackTransaction();
            throw $e;
        }
        return ['changedCount' => count($results)];
    }
}
