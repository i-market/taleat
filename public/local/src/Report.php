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
use Core\Env;

class Report {
    // TODO refactor: normalize fields, params, element structure. it's a mess.

    const STATUS_APPROVED = 60;
    const STATUS_REJECTED = 61;

    // TODO refactor args for different modes (new, edit)
    static function context($initial, $params, $result, $opts = []) {
        global $USER;
        if (!_::isEmpty($initial)) App::getInstance()->assert(self::validate($initial));

        $user = CUser::GetByID($USER->GetID())->GetNext();
        $today = date('d.m.Y');
        $suggestions = !_::get($params, 'suggest', true) ? [] : [
            'SC' => [
                'NAME' => $user['~WORK_COMPANY'],
                'DATA_ZAKL' => $today,
                'ADRES' => self::addressSuggestion($user),
                'PHONE' => $user['~WORK_PHONE']
            ],
            'IZDEL' => [
                'DATA_POSTUP' => $today
            ]
        ];
        // deep merge
        $fields = array_replace_recursive($suggestions, $initial, $params);
        $products = iter\toArray(Iblock::iter(CIBlockElement::GetList([], ["IBLOCK_ID"=>11, "ACTIVE"=>"Y"], false, false, ["ID", "NAME"])));
        $productId = _::get($fields, 'IZDEL.NAME');
        // TODO ux: natural sorting of models
        $models = is_numeric($productId)
            ? iter\toArray(Iblock::iter(CIBlockElement::GetProperty(11, $productId, "VALUE_ENUM", "asc", array("CODE"=>"MODELS"))))
            : [];
        $completeness = iter\toArray(Iblock::iter(CIBlockPropertyEnum::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>10, "CODE"=>"ITEM_COMPLECT"))));
        $defaults = [
            'mode' => 'new',
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
            'hasDefectDescription' => _::get($fields, 'DAN.DEFEKT') == 64,
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
        return array_merge($defaults, $opts);
    }

    static function isEditingDisallowed($elem) {
        global $USER;
        return $elem["PROPERTY_STATUS_ENUM_ID"] != self::STATUS_REJECTED || $elem["PROPERTY_USER_VALUE"] != $USER->GetID();
    }

    static function validate($fields) {
        // recursively check for keys
        $validate = function ($x, $spec) use (&$validate) {
            if (!is_array($x)) {
                return false;
            }
            if (is_string($spec)) {
                return array_key_exists($spec, $x);
            } else {
                return _::reduce($spec, function ($acc, $v, $k) use ($x, &$validate) {
                    $result = is_string($v) ? $validate($x, $v) : $validate(_::get($x, $k), $v);
                    return $acc && $result;
                }, true);
            }
        };
        return $validate($fields, [
            'SC' => [
                'NAME',
                'DATA_ZAKL',
                'ADRES',
                'PHONE',
            ],
            'VLADELEC' => [
                'FIO',
                'PHONE',
                'ADRES',
            ],
            'IZDEL' => [
                'NAME',
                'MODEL',
                'TYPE',
                'DATA_PROIZV',
                'DATA_PRODAJI',
                'KOMPLEKT',
                'DATA_POSTUP',
                'PRIZNAKI_NEISPR',
                'SVEDINIYA',
            ],
            'DAN' => array_merge([
                'VIEV_DEFEKT',
                'DEFEKT',
            ], _::get($fields, 'DAN.DEFEKT') == 64) ? ['DEFEKT3_DESCR'] : [],
            'PRICHINA',
            'ITEM_PLACE'
        ]);
    }

    static function element($id) {
        $arSelect = Array(
            "ID",
            "PROPERTY_NUMER",
            "PROPERTY_USER",
            "PROPERTY_SC_NAME",
            "PROPERTY_SC_PHONE",
            "PROPERTY_SC_ADDRESS",
            "PROPERTY_OWNER_TITLE",
            "PROPERTY_OWNER_PHONE",
            "PROPERTY_OWNER_ADDRESS",
            "PROPERTY_ITEM_NAME",
            "PROPERTY_ITEM_TYPE",
            "PROPERTY_ITEM_CREATED",
            "PROPERTY_ITEM_FAULT",
            "PROPERTY_MODEL",
            "PROPERTY_ITEM_REPAIRS",
            "PROPERTY_ITEM_REAL_FAULT",
            "PROPERTY_PRODUCT",
            "PROPERTY_ITEM_DATE_SALE",
            "PROPERTY_ITEM_DATE_GET",
            "PROPERTY_ITEM_COMPLECT",
            "PROPERTY_DATE_REPORT",
            "PROPERTY_USER_IMGS",
            "PROPERTY_REPORT_FAULT",
            "PROPERTY_USER_FAULT",
            "PROPERTY_REASON_FAIL_REPAIR",
            "PROPERTY_ITEM_PLACE",
            "PROPERTY_STATUS",
            "PROPERTY_USER"
        );
        return CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>IB_REPORTS, "ID"=>$id), false, false, $arSelect)->GetNext();
    }

    static function elementFields($elem) {
        $arItem = $elem;
        $filter = Array("IBLOCK_ID"=>11, "ACTIVE"=>"Y", 'NAME' => $arItem["PROPERTY_PRODUCT_VALUE"]);
        $product = CIBlockElement::GetList(Array(), $filter, false, false, Array("ID"))->GetNext();
        if ($product) {
            $models = Iblock::iter(CIBlockElement::GetProperty(11, $product['ID'], "sort", "asc", array("CODE"=>"MODELS")));
            $model = iter\search(function ($m) use ($arItem) {
                return $arItem["PROPERTY_MODEL_VALUE"] == $m["VALUE_ENUM"];
            }, $models);
        } else {
            $model = null;
        }
        $ret = [
            'SC' => [
                'NAME' => $arItem["PROPERTY_SC_NAME_VALUE"],
                'DATA_ZAKL' => $arItem["PROPERTY_DATE_REPORT_VALUE"],
                'ADRES' => $arItem["PROPERTY_SC_ADDRESS_VALUE"],
                'PHONE' => $arItem["PROPERTY_SC_PHONE_VALUE"],
            ],
            'VLADELEC' => [
                'FIO' => $arItem["PROPERTY_OWNER_TITLE_VALUE"],
                'PHONE' => $arItem["PROPERTY_OWNER_PHONE_VALUE"],
                'ADRES' => $arItem["PROPERTY_OWNER_ADDRESS_VALUE"],
            ],
            'IZDEL' => [
                'NAME' => $product['ID'],
                'MODEL' => $model['VALUE'],
                'TYPE' => $arItem["PROPERTY_ITEM_TYPE_VALUE"],
                'DATA_PROIZV' => $arItem["PROPERTY_ITEM_CREATED_VALUE"],
                'DATA_PRODAJI' => $arItem["PROPERTY_ITEM_DATE_SALE_VALUE"],
                'KOMPLEKT' => array_keys($arItem["PROPERTY_ITEM_COMPLECT_VALUE"]),
                'DATA_POSTUP' => $arItem["PROPERTY_ITEM_DATE_GET_VALUE"],
                'PRIZNAKI_NEISPR' => $arItem["PROPERTY_ITEM_FAULT_VALUE"],
                'SVEDINIYA' => $arItem["PROPERTY_ITEM_REPAIRS_VALUE"],
            ],
            'DAN' => array_merge([
                'VIEV_DEFEKT' => $arItem["PROPERTY_ITEM_REAL_FAULT_VALUE"],
                'DEFEKT' => $arItem["PROPERTY_REPORT_FAULT_ENUM_ID"],
            ], $arItem["PROPERTY_REPORT_FAULT_ENUM_ID"] == 64
                ? ['DEFEKT3_DESCR' => $arItem["PROPERTY_USER_FAULT_VALUE"]]
                : []),
            'PRICHINA' => $arItem["PROPERTY_REASON_FAIL_REPAIR_ENUM_ID"],
            'ITEM_PLACE' => $arItem["PROPERTY_ITEM_PLACE_ENUM_ID"]
        ];
        App::getInstance()->assert(self::validate($ret));
        return $ret;
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

    private static function templatePath() {
        $path =  Util::joinPath([$_SERVER['DOCUMENT_ROOT'], 'partneram/babyliss/tech-form/zakl_new1.xls']);
        // just in case someone deletes the file by accident
        $backup = Util::joinPath([$_SERVER['DOCUMENT_ROOT'], 'local/resources/reports/template.xls']);
        return file_exists($path) ? $path : $backup;
    }

    private static function filePath($num) {
        // TODO app-wide tmp dir
        $tmpDir = ini_get('upload_tmp_dir') ?: sys_get_temp_dir();
        return Util::joinPath([$tmpDir, "new_tz_".date("y")."_".$num.".xls"]);
    }

    static function create($params) {
        try {
            if (!self::_create($params)) {
                throw new \Exception();
            }
            return ['success' => true];
        } catch (\Exception $e) {
            if (App::env() === Env::DEV) {
                throw $e;
            }
            App::getInstance()->withRaven(function (\Raven_Client $raven) use ($e) {
                return $raven->captureException($e);
            });
            return [
                'success' => false,
                'message' => [
                    'type' => 'error',
                    'text' => View::genericErrorMessageHtml()
                ]
            ];
        }
    }

    static function update($params) {
        try {
            if (!self::_update($params)) {
                throw new \Exception();
            }
            return [
                'success' => true,
                'message' => [
                    'type' => 'success',
                    'text' => 'Изменения успешно сохранены и отправлены на проверку'
                ]
            ];
        } catch (\Exception $e) {
            if (App::env() === Env::DEV) {
                throw $e;
            }
            App::getInstance()->withRaven(function (\Raven_Client $raven) use ($e) {
                return $raven->captureException($e);
            });
            return [
                'success' => false,
                'message' => [
                    'type' => 'error',
                    'text' => View::genericErrorMessageHtml()
                ]
            ];
        }
    }

    private static function userImagesValue($files) {
        $arFiles = Array();
        foreach($files["tmp_name"] as $key=>$fileName):
            if($files["error"][$key] == 4) continue;

            $arTmpFile = CFile::MakeFileArray($fileName);
            $arTmpFile["name"] = $files["name"][$key];
            $arFiles[] = Array(
                "VALUE" => $arTmpFile,
                "DESCRIPTION" => $files["name"][$key],
            );
        endforeach;
        return $arFiles;
    }

    private static function _create($params) {
        global $USER, $_FILES;
        App::getInstance()->assert(self::validate($params));

        require_once $_SERVER["DOCUMENT_ROOT"].'/local/legacy/phpexcel/PHPExcel/IOFactory.php';

        $SC         = $params['SC'];
        $VLADELEC   = $params['VLADELEC'];
        $IZDEL      = $params['IZDEL'];
        $DAN        = $params['DAN'];
        $ZAP        = $params['ZAP'];
        $PRICHINA   = $params['PRICHINA'];
        $ITEM_PLACE = $params['ITEM_PLACE'];

        $arProduct = CIBlockElement::GetByID($params["IZDEL"]["NAME"]);
        $product = $arProduct->GetNext()["NAME"];

        $arPropModel = CIBlockPropertyEnum::GetByID($params["IZDEL"]["MODEL"]);
        $model = $arPropModel["VALUE"];

        $book = PHPExcel_IOFactory::load(self::templatePath());

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

        $KOMPLEKT = '';
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

        $objWriter->save(self::filePath($num));

        $PROP = array();
        $PROP["USER_IMGS"] = self::userImagesValue($_FILES["images"]);
        $PROP["NUMER"] = date("y")."/".$num;
        $PROP["FORMA"] = CFile::MakeFileArray(self::filePath($num));

        $PROP["USER"] = $USER->GetID();
        $PROP["SC_NAME"] = htmlspecialcharsBack($SC["NAME"]);
        $PROP["PRODUCT"] = $product;
        $PROP["ITEM_COMPLECT"] = $IZDEL["KOMPLEKT"];
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

        App::getInstance()->assert($OK);
        return $OK;
    }

    private static function _update($params) {
        global $_FILES;
        App::getInstance()->assert(isset($params['id']) && isset($params['NUMER']), 'illegal argument');
        App::getInstance()->assert(self::validate($params));

        require_once $_SERVER["DOCUMENT_ROOT"].'/local/legacy/phpexcel/PHPExcel/IOFactory.php';

        $itemID = $params["id"];
        $arProduct = CIBlockElement::GetByID($params["IZDEL"]["NAME"]);
        $product = $arProduct->GetNext()["NAME"];
        $arPropModel = CIBlockPropertyEnum::GetByID($params["IZDEL"]["MODEL"]);
        $model = $arPropModel["VALUE"];
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("SC_NAME"=>$params["SC"]["NAME"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("DATE_REPORT"=>$params["SC"]["DATA_ZAKL"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("SC_ADDRESS"=>$params["SC"]["ADRES"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("SC_PHONE"=>$params["SC"]["PHONE"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("OWNER_TITLE"=>$params["VLADELEC"]["FIO"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("OWNER_PHONE"=>$params["VLADELEC"]["PHONE"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("OWNER_ADDRESS"=>$params["VLADELEC"]["ADRES"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("PRODUCT"=>$product));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("MODEL"=>$model));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("ITEM_TYPE"=>$params["IZDEL"]["TYPE"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("ITEM_CREATED"=>$params["IZDEL"]["DATA_PROIZV"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("ITEM_DATE_SALE"=>$params["IZDEL"]["DATA_PRODAJI"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("ITEM_COMPLECT"=>$params["IZDEL"]["KOMPLEKT"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("ITEM_DATE_GET"=>$params["IZDEL"]["DATA_POSTUP"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("ITEM_FAULT"=>$params["IZDEL"]["PRIZNAKI_NEISPR"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("ITEM_REAL_FAULT"=>$params["DAN"]["VIEV_DEFEKT"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("REPORT_FAULT"=>$params["DAN"]["DEFEKT"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("ITEM_PLACE"=>$params["ITEM_PLACE"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("ITEM_REPAIRS"=>$params["IZDEL"]["SVEDINIYA"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("USER_FAULT"=>$params["DAN"]["DEFEKT3_DESCR"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("REASON_FAIL_REPAIR"=>$params["PRICHINA"]));
        $arFiles = self::userImagesValue($_FILES["images"]);
        if (count($arFiles)):
            CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, array("USER_IMGS" => $arFiles));
        endif;

        $book = PHPExcel_IOFactory::load(self::templatePath());
        $book->getActiveSheet()->setCellValue('A1', "Техническое заключение на изделие BABYLISS №".$params["NUMER"]);
        $book->getActiveSheet()->setCellValue('A4', $params["SC"]["NAME"]);
        $book->getActiveSheet()->setCellValue('F3', $params["SC"]["DATA_ZAKL"]);
        $book->getActiveSheet()->setCellValue('A8', $params["SC"]["ADRES"]);
        $book->getActiveSheet()->setCellValue('D7', $params["SC"]["PHONE"]);

        $book->getActiveSheet()->setCellValue('B10', $params["VLADELEC"]["FIO"]);
        $book->getActiveSheet()->setCellValue('H10', $params["VLADELEC"]["PHONE"]);
        $book->getActiveSheet()->setCellValue('A11', $params["VLADELEC"]["ADRES"]);

        $book->getActiveSheet()->setCellValue('C13', $product);
        $book->getActiveSheet()->setCellValue('C14', $model);
        $book->getActiveSheet()->setCellValue('H12', $params["IZDEL"]["TYPE"]);
        $book->getActiveSheet()->setCellValue('I14', $params["IZDEL"]["DATA_PROIZV"]);
        $book->getActiveSheet()->setCellValue('C15', $params["IZDEL"]["DATA_PRODAJI"]);

        $COMPLECT = '';
        foreach ($params["IZDEL"]["KOMPLEKT"] as $key=>$propID):
            $arComplect = CIBlockPropertyEnum::GetByID($propID);
            if($key > 0) $COMPLECT .= ", ";
            $COMPLECT .= $arComplect["VALUE"];
        endforeach;

        $book->getActiveSheet()->setCellValue('C16', $COMPLECT);
        $book->getActiveSheet()->setCellValue('E17', $params["IZDEL"]["DATA_POSTUP"]);
        $book->getActiveSheet()->setCellValue('A19', $params["IZDEL"]["PRIZNAKI_NEISPR"]);
        $book->getActiveSheet()->setCellValue('E20', $params["IZDEL"]["SVEDINIYA"]);
        $book->getActiveSheet()->setCellValue('D23', $params["DAN"]["VIEV_DEFEKT"]);

        $arPropReportFault = CIBlockPropertyEnum::GetByID($params["DAN"]["DEFEKT"]);
        $book->getActiveSheet()->setCellValue('F24', $arPropReportFault["VALUE"]);
        if($params["REPORT_FAULT"] == "64"):
            $book->getActiveSheet()->setCellValue('A25', $params["DAN"]["DEFEKT3_DESCR"]);
        endif;

        $arPropReasonFailRepair = CIBlockPropertyEnum::GetByID($params["PRICHINA"]);
        $book->getActiveSheet()->setCellValue('A34', $arPropReasonFailRepair["VALUE"]);

        $arPropItemPlace = CIBlockPropertyEnum::GetByID($params["ITEM_PLACE"]);
        $book->getActiveSheet()->setCellValue('A36', $arPropItemPlace["VALUE"]);

        $objWriter = PHPExcel_IOFactory::createWriter($book, 'Excel5');
        list($_, $num) = explode('/', $params["NUMER"]);
        $objWriter->save(self::filePath($num));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("FORMA"=>CFile::MakeFileArray(self::filePath($num))));

        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, array("STATUS" => 70));
        $arEventFields = array(
            "NOMER"             => $params["NUMER"],
            "DATA_OTPR"         => date("d.m.Y"),
            "URL"               => "http://".SITE_SERVER_NAME."/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=10&type=region&ID=".$itemID."&lang=ru&find_section_section=0&WF=Y"
        );
        $item = CIBlockElement::GetList(Array(), [], false, Array("nTopCount"=>1), Array("ID", "PROPERTY_FORMA"))->GetNext();
        CEvent::Send("TEH_ZAKL_EDIT", "s1", $arEventFields, "N", "", array($item["PROPERTY_FORMA_VALUE"]));

        return true;
    }
}