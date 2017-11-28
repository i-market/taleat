<?php

namespace App;

use CEvent;
use CFile;
use CIBlockElement;
use CIBlockPropertyEnum;
use CModule;
use Core\Underscore as _;
use CUser;
use Core\Strings as str;
use iter;
use PHPExcel_IOFactory;
use Core\Util;

class Report {
    const STATUS_APPROVED = 60;
    const STATUS_REJECTED = 61;

    static function context($params, $result) {
        global $USER;
        $user = CUser::GetByID($USER->GetID())->GetNext();
        $products = iter\toArray(Iblock::iter(CIBlockElement::GetList([], ["IBLOCK_ID"=>11, "ACTIVE"=>"Y"], false, false, ["ID", "NAME"])));
        $productId = _::get($params, 'IZDEL.NAME');
        // TODO ux: natural sorting of models
        $models = is_numeric($productId)
            ? iter\toArray(Iblock::iter(CIBlockElement::GetProperty(11, $productId, "VALUE_ENUM", "asc", array("CODE"=>"MODELS"))))
            : [];
        $completeness = iter\toArray(Iblock::iter(CIBlockPropertyEnum::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>10, "CODE"=>"ITEM_COMPLECT"))));
        $today = date('d.m.Y');
        $fields = array_replace_recursive($params, !_::get($params, 'suggest', true) ? [] : [
            'SC' => [
                'NAME' => _::get($params, 'SC.NAME', $user['~WORK_COMPANY']),
                'DATA_ZAKL' => _::get($params, 'SC.DATA_ZAKL', $today),
                'ADRES' => _::get($params, 'SC.ADRES', self::addressSuggestion($user)),
                'PHONE' => _::get($params, 'SC.PHONE', $user['~WORK_PHONE'])
            ],
            'IZDEL' => [
                'DATA_POSTUP' => _::get($params, 'IZDEL.DATA_POSTUP', $today)
            ]
        ]);
        return [
            'result' => $result,
            'fields' => self::escapeRec($fields),
            'products' => $products,
            'models' => $models,
            'completeness' => $completeness,
            // hardcoded ids
            'defects' => [
                62 => 'Заводской дефект',
                63 => 'Механические повреждения',
                64 => 'Нарушение правил эксплуатации',
            ],
            'hasDefectDescription' => _::get($params, 'DAN.DEFEKT') == 64,
            'reasons' => [
                65 => 'Распоряжение фирмы-изготовителя',
                66 => 'Отказ владельца от ремонта в соответствии с «Законом о защите прав потребителей»',
                67 => 'Отсутствие возможности получения необходимой замены изделия',
            ],
            'places' => [
                68 => 'Выдано на руки владельцу',
                69 => 'Оставлено в сервисном центре на ответственное хранение',
            ]
        ];
    }

    static function escapeRec($x) {
        if (is_string($x)) {
            return View::escAttr($x);
        } elseif (is_array($x)) {
            return array_map([self::class, 'escapeRec'], $x);
        } else {
            return $x;
        }
    }

    static function addressSuggestion($user) {
        // from highest to lowest priority
        $prefixes = ['WORK', 'PERSONAL'];
        // ordered
        $suffixes = ['STATE', 'ZIP', 'CITY', 'STREET'];
        $candidates = _::map($prefixes, function ($prefix) use ($user, $suffixes) {
            return array_reduce($suffixes, function ($m, $s) use ($prefix, $user) {
                $v = $user['~'.$prefix.'_'.$s];
                return str::isEmpty($v) ? $m : _::set($m, $s, $v);
            }, ['prefix' => $prefix]);
        });
        // mutate
        assert(usort($candidates, function ($x, $y) use ($prefixes) {
            $diff = count($y) - count($x);
            // pick the most complete address
            return $diff !== 0
                ? $diff
                : array_search($x['prefix'], $prefixes) - array_search($y['prefix'], $prefixes);
        }));
        $parts = _::remove(_::first($candidates), 'prefix');
        if (_::isEmpty($parts)) {
            return '';
        }
        return join(', ', array_reduce($suffixes, function ($acc, $s) use ($parts) {
            $v = _::get($parts, $s, '');
            return str::isEmpty($v) ? $acc : _::append($acc, $v);
        }, []));
    }

    static function create($params) {
        try {
            if (!self::_create($params)) {
                throw new \Exception();
            }
            return ['success' => true];
        } catch (\Exception $e) {
            App::getInstance()->assert(false);
            return [
                'success' => false,
                'message' => [
                    'type' => 'error',
                    'text' => View::genericErrorMessageHtml()
                ]
            ];
        }
    }

    private static function _create($params) {
        global $USER;

        require_once $_SERVER["DOCUMENT_ROOT"].'/local/legacy/phpexcel/PHPExcel/IOFactory.php';

        $templatePath = Util::joinPath([$_SERVER['DOCUMENT_ROOT'], 'partneram/babyliss/tech-form/zakl_new1.xls']);
        $filePath = function ($num) {
            $tmpDir = ini_get('upload_tmp_dir') ?: sys_get_temp_dir();
            return Util::joinPath([$tmpDir, "new_tz_".date("y")."_".$num.".xls"]);
        };

        $SC         = $params['SC'];
        $VLADELEC   = $params['VLADELEC'];
        $IZDEL      = $params['IZDEL'];
        $KOMPLEKT   = $params['KOMPLEKT'];
        $DAN        = $params['DAN'];
        $ZAP        = $params['ZAP'];
        $PRICHINA   = $params['PRICHINA'];
        $ITEM_PLACE = $params['ITEM_PLACE'];

        $arProduct = CIBlockElement::GetByID($params["IZDEL"]["NAME"]);
        $product = $arProduct->GetNext()["NAME"];

        $arPropModel = CIBlockPropertyEnum::GetByID($params["IZDEL"]["MODEL"]);
        $model = $arPropModel["VALUE"];

        $book = PHPExcel_IOFactory::load($templatePath);

        $date = date("Y")."-01-01";
        $filter = array(
            "IBLOCK_ID"=>10,
            "ACTIVE"=>"Y",
            ">=DATE_ACTIVE_FROM" => ConvertTimeStamp(strtotime($date),"FULL"),
        );
        $num = 1;
        $res = CIBlockElement::GetList(Array("ID"=>"DESC"), $filter, false, Array("nTopCount"=>1), Array("ID", "PROPERTY_NUMER"));
        if($ob = $res->Fetch()){
            $num = $ob["PROPERTY_NUMER_VALUE"];
            $num = explode("/", $num);
            $num = $num[1]+1;
            for ($x = strlen($num); $x < 6; $x++):
                $num = "0".$num;
            endfor;
        };

        $book->getActiveSheet()->setCellValue('A1', "Техническое заключение на изделие BABYLISS №".date("y")."/".$num);
        $book->getActiveSheet()->setCellValue('A4', $SC["NAME"]);
        $book->getActiveSheet()->setCellValue('F3', $SC["DATA_ZAKL"]);
        $book->getActiveSheet()->setCellValue('A8', $SC["ADRES"]);
        $book->getActiveSheet()->setCellValue('D7', $SC["PHONE"]);

        $book->getActiveSheet()->setCellValue('B10', $VLADELEC["FIO"]);
        $book->getActiveSheet()->setCellValue('H10', $VLADELEC["PHONE"]);
        $book->getActiveSheet()->setCellValue('A11', $VLADELEC["ADRES"]);

        $book->getActiveSheet()->setCellValue('C13', $product);
        $book->getActiveSheet()->setCellValue('C14', $model);
        $book->getActiveSheet()->setCellValue('H12', $IZDEL["TYPE"]);
        $book->getActiveSheet()->setCellValue('I14', $IZDEL["DATA_PROIZV"]);
        $book->getActiveSheet()->setCellValue('C15', $IZDEL["DATA_PRODAJI"]);

        foreach ($IZDEL["KOMPLEKT"] as $key=>$propID):
            $arComplect = CIBlockPropertyEnum::GetByID($propID);
            if($key > 0) $KOMPLEKT .= ", ";
            $KOMPLEKT .= $arComplect["VALUE"];
        endforeach;

        $book->getActiveSheet()->setCellValue('C16', $KOMPLEKT);
        $book->getActiveSheet()->setCellValue('E17', $IZDEL["DATA_POSTUP"]);
        $book->getActiveSheet()->setCellValue('A19', $IZDEL["PRIZNAKI_NEISPR"]);
        $book->getActiveSheet()->setCellValue('E20', $IZDEL["SVEDINIYA"]);

        $book->getActiveSheet()->setCellValue('D23', $DAN["VIEV_DEFEKT"]);
        switch($DAN["DEFEKT"]){
            case 62:
                $DEFEKT = "Заводской дефект";
                break;
            case 63:
                $DEFEKT = "Механические повреждения";
                break;
            case 64:
                $DEFEKT = "Нарушение правил эксплуатации";
                $book->getActiveSheet()->setCellValue('A25', $DAN["DEFEKT3_DESCR"]);
                break;
        }

        $book->getActiveSheet()->setCellValue('F24', $DEFEKT);

        $start_pos = 29;
        foreach($ZAP as $key=>$ob) {
            if($key > 2) break;
            if($ob["NAME"] && $ob["ART"]){
                $book->getActiveSheet()->setCellValue('A'.$start_pos, $ob["NAME"]);
                $book->getActiveSheet()->setCellValue('E'.$start_pos, $ob["ART"]);
                if ($ob["SKLAD"]) $ob["SKLAD"] = "Да";
                else $ob["SKLAD"] = "Нет";

                $book->getActiveSheet()->setCellValue('I'.$start_pos, $ob["SKLAD"]);
                $start_pos++;
            }
        }

        switch($PRICHINA){
            case 65:
                $TPRICHINA = "Распоряжение фирмы-изготовителя";
                break;
            case 66:
                $TPRICHINA = "Отказ владельца от ремонта в соответствии с «Законом о защите прав потребителей»";
                break;
            case 67:
                $TPRICHINA = "Отсутствие возможности получения необходимой замены изделия";
                break;
        }
        $book->getActiveSheet()->setCellValue('A34', $TPRICHINA);
        switch($ITEM_PLACE):
            case 68:
                $book->getActiveSheet()->setCellValue('A36', "Выдано на руки владельцу");
                break;

            case 69:
                $book->getActiveSheet()->setCellValue('A36', "Оставлено в сервисном центре на ответственное хранение");
                break;

            default: break;
        endswitch;

        $objWriter = PHPExcel_IOFactory::createWriter($book, 'Excel5');

        CModule::IncludeModule("iblock");
        $el = new CIBlockElement;

        $objWriter->save($filePath($num));

        $PROP = array();
        $imgs = array();
        $is_image = CFile::IsImage($_FILES["img1"]["name"], $_FILES["img1"]["type"]);
        if ($is_image){
            $img1 = CFile::SaveFile($_FILES["img1"], "tech-zakl-imgs");
            $img1 = CFile::MakeFileArray($img1);
            $imgs[] = $img1;
        }

        $is_image = CFile::IsImage($_FILES["img2"]["name"], $_FILES["img2"]["type"]);
        if ($is_image){
            $img2 = CFile::SaveFile($_FILES["img2"], "tech-zakl-imgs");
            $img2 = CFile::MakeFileArray($img2);
            $imgs[] = $img2;
        }

        $is_image = CFile::IsImage($_FILES["img3"]["name"], $_FILES["img3"]["type"]);
        if ($is_image){
            $img3 = CFile::SaveFile($_FILES["img3"], "tech-zakl-imgs");
            $img3 = CFile::MakeFileArray($img3);
            $imgs[] = $img3;
        }
        $PROP["USER_IMGS"] = $imgs;
        $PROP["NUMER"] = date("y")."/".$num;
        $PROP["FORMA"] = CFile::MakeFileArray($filePath($num));

        $PROP["USER"] = $USER->GetID();
        $PROP["SC_NAME"] = htmlspecialcharsBack($SC["NAME"]);
        $PROP["PRODUCT"] = $product;
        $PROP["ITEM_COMPLECT"] = $_POST["IZDEL"]["KOMPLEKT"];
        $PROP["STATUS"] = Array("VALUE" => 59);
        $PROP["DATE_REPORT"] = $SC["DATA_ZAKL"];
        $PROP["SC_PHONE"] = $SC["PHONE"];
        $PROP["SC_ADDRESS"] = $SC["ADRES"];
        $PROP["OWNER_TITLE"] = $VLADELEC["FIO"];
        $PROP["OWNER_PHONE"] = $VLADELEC["PHONE"];
        $PROP["OWNER_ADDRESS"] = $VLADELEC["ADRES"];
        $PROP["MODEL"] = $model;
        $PROP["ITEM_TYPE"] = $IZDEL["TYPE"];
        $PROP["ITEM_CREATED"] = $IZDEL["DATA_PROIZV"];
        $PROP["ITEM_DATE_SALE"] = $IZDEL["DATA_PRODAJI"];
        $PROP["ITEM_DATE_GET"] = $IZDEL["DATA_POSTUP"];
        $PROP["ITEM_FAULT"] = $IZDEL["PRIZNAKI_NEISPR"];
        $PROP["ITEM_REPAIRS"] = $IZDEL["SVEDINIYA"];
        $PROP["ITEM_REAL_FAULT"] = $DAN["VIEV_DEFEKT"];
        $PROP["REPORT_FAULT"] = $DAN["DEFEKT"];
        if($DAN["DEFEKT"] == 64) $PROP["USER_FAULT"] = $DAN["DEFEKT3_DESCR"];
        else $PROP["USER_FAULT"] = "";
        $PROP["REASON_FAIL_REPAIR"] = $PRICHINA;
        $PROP["ITEM_PLACE"] = $ITEM_PLACE;

        $data = date("d.m.Y");
        $arLoadProductArray = Array(
            "IBLOCK_ID"      => 10,
            "PROPERTY_VALUES"=> $PROP,
            "NAME"           => "Техническое заключение ".date("y")."/".$num,
            "ACTIVE"         => "Y",
            "DATE_ACTIVE_FROM" => $data
        );

        if($ID = $el->Add($arLoadProductArray)){
            $OK = true;
            $arEventFields = array(
                "NOMER"             => date("y")."/".$num,
                "DATA_OTPR"         => $data,
                "URL"               => "http://".SITE_SERVER_NAME."/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=10&type=region&ID=".$ID."&lang=ru&find_section_section=0&WF=Y"
            );

            $arSelect = Array("ID", "NAME", "PROPERTY_FORMA");
            $arFilter = Array("IBLOCK_ID"=>10, "ACTIVE"=>"Y", "ID"=>$ID);
            $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nTopCount"=>1), $arSelect);
            if($ob = $res->Fetch()){
                $fid = $ob["PROPERTY_FORMA_VALUE"];
            }

            CEvent::Send("TEH_ZAKL", "s1", $arEventFields, "N", "", array($fid));
        } else {
            $OK = false;
        }

        unlink($filePath($num));

        App::getInstance()->assert($OK);
        return $OK;
    }
}