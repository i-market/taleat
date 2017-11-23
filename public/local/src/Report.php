<?php

namespace App;

use CIBlockElement;
use CIBlockPropertyEnum;
use Core\Underscore as _;
use CUser;
use Core\Strings as str;
use iter;

class Report {
    static function context($params) {
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
}