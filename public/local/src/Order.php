<?php

namespace App;

use Core\Underscore as _;
use Core\Util;

class Order {
    /** самовывоз */
    const LOCAL_PICKUP = '2';
    const CSE = '27';
    const RU_POST = '28';

    static function isPayable($order) {
        return $order['STATUS_ID'] === OrderStatus::ACCEPTED
            && $order['PAYED'] !== 'Y'
            && $order['CANCELED'] !== 'Y';
    }

    // TODO hack: clean up locations
    static function locationsUniqueBy($xs, callable $f) {
        $keep = [5773]; // locations with dependencies, keep them
        $ret = [];
        $seen = [];
        foreach ($xs as $x) {
            if (!in_array($f($x), $seen) || in_array($x['ID'], $keep)) {
                $ret[] = $x;
            }
            $seen[] = $f($x);
        }
        return $ret;
    }

    // TODO hack
    static function filterDeliveryServices($xs, $location) {
        $moscow = [5773, 6322];
        $stPetersburg = [6002, 6333];
        if (in_array($location, $moscow)) {
            return _::remove($xs, self::RU_POST);
        } else {
            return $xs;
        }
    }
    
    static function deliveryServiceOrgInfo($id) {
        $data = [
            self::RU_POST => [
                'name' => 'Почта России',
                'url' => 'https://www.pochta.ru/'
            ],
            self::CSE => [
                'name' => 'Курьерская служба КСЭ',
                'url' => 'http://www.cse.ru/lang-rus/'
            ]
        ];
        return _::get($data, $id);
    }

    /** квитанция сбербанка */
    static function invoiceWriter($orderId) {
        require_once $_SERVER["DOCUMENT_ROOT"].'/local/legacy/phpexcel/PHPExcel/IOFactory.php';

        $templatePath = Util::joinPath([$_SERVER['DOCUMENT_ROOT'], 'local/resources/pd4.xls']);

        $ID = $orderId;
        /** @noinspection PhpDynamicAsStaticMethodCallInspection */
        $order = \CSaleOrder::GetByID($ID);
        if(!$order || $order["PAY_SYSTEM_ID"] != "5") die();
        $date = ParseDateTime($order["DATE_INSERT"], "YYYY-MM-DD MI:SS");
        $orderDate = $date["DD"].".".$date["MM"].".".$date["YYYY"];
        $price = $order["PRICE"];
        $nameProp = \CSaleOrderPropsValue::GetList(Array(), Array("ORDER_ID"=>$ID, "CODE"=>"IMYA"))->GetNext();
        $lastnameProp = \CSaleOrderPropsValue::GetList(Array(), Array("ORDER_ID"=>$ID, "CODE"=>"FAM"))->GetNext();
        $secondnameProp = \CSaleOrderPropsValue::GetList(Array(), Array("ORDER_ID"=>$ID, "CODE"=>"OTCHESTVO"))->GetNext();
        $addressProp = \CSaleOrderPropsValue::GetList(Array(), Array("ORDER_ID"=>$ID, "CODE"=>"ADDRESS"))->GetNext();
        $fio = $lastnameProp["VALUE"] . " " . $nameProp["VALUE"] . " " . $secondnameProp["VALUE"];
        $address = $addressProp["VALUE"];
        $book = \PHPExcel_IOFactory::load($templatePath);
        $book->getActiveSheet()->setCellValue('E11', "Оплата заказа №".$ID." от ".$orderDate);
        $book->getActiveSheet()->setCellValue('E31', "Оплата заказа №".$ID." от ".$orderDate);
        $book->getActiveSheet()->setCellValue('L15', $price);
        $book->getActiveSheet()->setCellValue('L35', $price);
        $book->getActiveSheet()->setCellValue('N13', $fio);
        $book->getActiveSheet()->setCellValue('N33', $fio);
        $book->getActiveSheet()->setCellValue('N14', $address);
        $book->getActiveSheet()->setCellValue('N34', $address);
        $objWriter = \PHPExcel_IOFactory::createWriter($book, 'Excel5');
        return $objWriter;
    }

    static function handleInvoice($orderId) {
        global $APPLICATION;
        $writer = self::invoiceWriter($orderId);
        $filename = "pd4_{$orderId}.xls";
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
}
