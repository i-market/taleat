<?php

namespace Core;

use Core\Underscore as _;
use Core\Strings as str;

class Util {
    static private $lastId = 0;

    static function uniqueId() {
        self::$lastId += 1;
        return self::$lastId;
    }
    
    static function inRange($x, $min, $max) {
        return $x >= $min && $x <= $max;
    }

    // TODO varargs
    static function joinPath(array $paths) {
        $trimmed = _::clean(array_map(function($path) {
            return trim($path, DIRECTORY_SEPARATOR);
        }, $paths));
        $prefix = str::startsWith(_::first($paths), DIRECTORY_SEPARATOR) ? DIRECTORY_SEPARATOR : '';
        return $prefix.join(DIRECTORY_SEPARATOR, $trimmed);
    }

    static function url($path) {
        // TODO check for leading slash in path?
        $host = _::first(explode(':', $_SERVER['HTTP_HOST']));
        $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443;
        return ($isHttps ? 'https' : 'http').'://'.$host.$path;
    }

    static function formInputNamePath($name) {
        $segments = array_map(_::partialRight('trim', ']'), explode('[', $name));
        return array_filter($segments, _::negate([str::class, 'isEmpty']));
    }

    static function humanFileSize($size, $_opts = []) {
        $units = ['Б','КБ','МБ','ГБ','ТБ','ПБ','EB','ZB','YB'];
        $opts = array_merge(array_merge(['precision' => 0], $_opts), [
            'units' => array_merge($_opts['units'], _::drop($units, count($_opts['units'])))
        ]);
        $step = 1024;
        $i = 0;
        while (($size / $step) > 0.9) {
            $size = $size / $step;
            $i++;
        }
        return round($size, $opts['precision']).' '.$opts['units'][$i];
    }

    static function monthRu($n) {
        $months = explode('|', '|января|февраля|марта|апреля|мая|июня|июля|августа|сентября|октября|ноября|декабря');
        return $months[$n];
    }

    static function splitFileExtension($path) {
        $filename = str::contains($path, DIRECTORY_SEPARATOR)
            ? self::basename($path)
            : $path;
        $parts = explode('.', $filename);
        if (str::startsWith($filename, '.')) {
            return [$filename, ''];
        } elseif (count($parts) < 2) {
            return [$filename, ''];
        } else {
            return [join('', _::initial($parts)), _::last($parts)];
        }
    }

    // cyrillic characters break built-in `basename`
    static function basename($path) {
        return _::last(explode(DIRECTORY_SEPARATOR, $path));
    }

    /**
     * Возвращает единицу измерения с правильным окончанием
     * @param $number int Число
     * @param $cases array Варианты слова {nom: 'час', gen: 'часа', plu: 'часов'}
     * @return string
     */
    static function units($number, $cases) {
        // shorthand syntax
        $args = func_get_args();
        if (count($args) === 4) {
            $cases = array('nom' => $args[1], 'gen' => $args[2], 'plu' => $args[3]);
        }
        $num = abs($number);
        return (mb_strpos(strval($num), '.') !== false
            ? $cases['gen']
            : ($num % 10 === 1 && $num % 100 !== 11
                ? $cases['nom']
                : ($num % 10 >= 2 && $num % 10 <= 4 && ($num % 100 < 10 || $num % 100 >= 20)
                    ? $cases['gen']
                    : $cases['plu'])));
    }

    static function formatCurrency($num, $options = []) {
        $opts = array_merge([
            'cents' => true
        ], $options);
        return number_format($num, $opts['cents'] ? 2 : 0, ',', ' ');
    }

    static function ensureList($x) {
        return !is_array($x) || !_::isIndexed($x) ? [$x] : $x;
    }

    static function descendants($parents, $childrenFn, $ret = []) {
        if (is_string($childrenFn)) {
            $path = $childrenFn;
            $childrenFn = function($array) use ($path) {
                return _::get($array, $path);
            };
        }
        $children = _::flatMap($parents, $childrenFn);
        if (_::isEmpty($children)) {
            return $ret;
        } else {
            return self::descendants($children, $childrenFn, array_merge($ret, $children));
        }
    }

    static function parseLatLong($str) {
        $matchesRef = [];
        if (preg_match('/(\-?\d+(?:\.\d+)?),\s*(\-?\d+(?:\.\d+)?)/', $str, $matchesRef)) {
            list($_, $lat, $long) = $matchesRef;
            return ['lat' => floatval($lat), 'long' => floatval($long)];
        } else {
            return null;
        }
    }
}