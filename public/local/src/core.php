<?php

namespace Core;

use ArrayIterator;
use Bitrix\Main\Config\Configuration;
use CBitrixComponentTemplate;
use CFile;
use CIBlock;
use Closure;
use Core\Underscore as _;
use Underscore\Methods\ArraysMethods;
use Underscore\Methods\StringsMethods;

class Underscore extends ArraysMethods {
    /**
     * @param mixed $collection
     * @param array|string $key
     * @param mixed $value
     * @return mixed
     */
    static function set($collection, $key, $value) {
        if (is_string($key)) {
            return parent::set($collection, $key, $value);
        }
        $ref = &$collection;
        foreach ($key as $k) {
            if (!is_array($ref)) {
                $ref = [];
            }
            $ref = &$ref[$k];
        }
        $ref = $value;
        return $collection;
    }

    /**
     * @param array $collection
     * @param array|string $key
     * @param mixed $default
     * @return mixed
     */
    static function get($collection, $key, $default = null) {
        if (is_string($key)) {
            return parent::get($collection, $key, $default);
        }
        $ret = $collection;
        foreach ($key as $k) {
            if (!isset($ret[$k])) {
                return $default instanceof Closure ? $default() : $default;
            }
            // TODO add object support
            $ret = $ret[$k];
        }
        return $ret;
    }

    // TODO string callables support
    static function map($array, $f) {
        $ret = [];
        foreach ($array as $k => $v) {
            $ret[$k] = is_string($f) ? self::get($v, $f) : $f($v, $k);
        }
        return $ret;
    }

    static function flatMap($array, $f) {
        $ret = [];
        foreach ($array as $k => $v) {
            $xs = is_string($f) ? self::get($v, $f) : $f($v, $k);
            foreach ($xs as $x) {
                $ret[] = $x;
            }
        }
        return $ret;
    }

    static function flattenDeep($array, callable $pred = null) {
        $pred = $pred ?: self::complement('is_array');
        $reducer = function($acc, $x) use (&$reducer, $pred) {
            if ($pred($x)) {
                return self::append($acc, $x);
            } else {
                return array_merge($acc, array_reduce($x, $reducer, []));
            }
        };
        return array_reduce($array, $reducer, []);
    }

    static function mapKeys($array, $f) {
        $ret = [];
        foreach ($array as $k => $v) {
            $result = is_string($f) ? self::get($v, $f) : $f($v, $k);
            $ret[$result] = $v;
        }
        return $ret;
    }

    // TODO strict by default
    static function pick($array, $keys, $strict = null) {
        return array_filter($array, function ($key) use ($keys, $strict) {
            return in_array($key, $keys, $strict);
        }, ARRAY_FILTER_USE_KEY);
    }

    static function reduce($array, $f, $initial) {
        return array_reduce(array_keys($array), function($ret, $k) use ($array, $f) {
            return $f($ret, $array[$k], $k);
        }, $initial);
    }

    static function filter($array, $pred = null) {
        /** @var callable $pred */
        if ($pred === null) {
            return self::clean($array);
        }
        $ret = array_filter($array, function($key) use ($array, $pred) {
            return $pred($array[$key], $key);
        }, ARRAY_FILTER_USE_KEY);
        // restore indices
        return self::isIndexed($array) ? array_values($ret) : $ret;
    }
    
    static function drop($array, $n) {
        return array_slice($array, $n);
    }

    static function take($array, $n, $preserveKeys = null) {
        return array_slice($array, 0, $n, $preserveKeys ?: !self::isIndexed($array));
    }

    static function takeWhile($array, $pred) {
        $ret = [];
        foreach ($array as $x) {
            if (!$pred($x)) {
                return $ret;
            }
            $ret[] = $x;
        }
        return $ret;
    }

    static function dropWhile($array, $pred) {
        $from = 0;
        foreach ($array as $k => $v) {
            if (!$pred($v, $k)) {
                return array_slice($array, $from);
            }
            $from += 1;
        }
        return [];
    }

    static function update($array, $key, callable $f) {
        return !self::has($array, $key)
            ? $array
            : self::set($array, $key, $f(self::get($array, $key)));
    }

    static function isEmpty($x) {
        return is_array($x) && count($x) === 0;
    }

    // TODO seems like a bad heuristic to rely on
    static function isIndexed(array $array) {
        if (!is_array($array)) return false;
        return isset($array[0]);
    }

    /** @deprecated use underscore.php `group` */
    static function groupBy($array, $f) {
        $ret = [];
        foreach ($array as $x) {
            $key = is_string($f) ? self::get($x, $f) : $f($x);
            $ret[$key][] = $x;
        }
        return $ret;
    }

    static function minBy($array, callable $f) {
        return array_reduce($array, function($ret, $x) use ($f) {
            return $ret === null || $f($x) < $f($ret) ? $x : $ret;
        });
    }

    static function maxBy($array, callable $f) {
        return array_reduce($array, function($ret, $x) use ($f) {
            return $ret === null || $f($x) > $f($ret) ? $x : $ret;
        });
    }

    // TODO inconsistent argument ordering
    static function keyBy($by, $array) {
        // TODO add callable support
        assert(is_string($by));
        $ret = [];
        foreach ($array as $x) {
            $ret[$x[$by]] = $x;
        }
        return $ret;
    }

    /**
     * @return array returns an array of [take(n), drop(n)]
     */
    static function splitAt($array, $n) {
        return [self::take($array, $n), self::drop($array, $n)];
    }

    static function findKey($array, $pred) {
        foreach ($array as $key => $value) {
            if ($pred($value, $key)) {
                return $key;
            }
        }
        return null;
    }
    
    static function renameKeys(array $map, array $keyMap) {
        return self::mapKeys($map, function($_, $k) use ($keyMap) {
            return self::get($keyMap, $k, $k);
        });
    }

    // TODO refactor: unwrap
    static function identity() {
        return function($x) {
            return $x;
        };
    }

    static function constantly($x) {
        return function() use ($x) {
            return $x;
        };
    }

    // TODO varargs
    static function compose(callable $f, callable $g) {
        return function(...$args) use ($f, $g) {
            return $f($g(...$args));
        };
    }

    /** aka juxtapose */
    static function over($fns, ...$args) {
        $ret = function(...$args) use ($fns) {
            return array_map(function($f) use ($args) {
                return $f(...$args);
            }, $fns);
        };
        return self::isEmpty($args) ? $ret : $ret(...$args);
    }

    static function complement(callable $f) {
        return function(...$args) use ($f) {
            return !$f(...$args);
        };
    }

    static function partial(callable $f, ...$args) {
        return function (...$rest) use ($f, $args) {
            return $f(...array_merge($args, $rest));
        };
    }

    static function partialRight(callable $f, ...$args) {
        return function (...$rest) use ($f, $args) {
            return $f(...array_merge($rest, $args));
        };
    }

    /** useful for inline type hints */
    static function func(callable $x) {
        return $x;
    }

    /**
     * supports: '.', '+', '-', '*', and '/' operators
     * @param string $operator infix operator to use
     * @return callable
     */
    static function operator($operator) {
        static $cache;
        if (!$cache) {
            $cache = [
                '.' => function($x, $y) { return $x . $y; },
                '+' => function($x, $y) { return $x + $y; },
                '-' => function($x, $y) { return $x - $y; },
                '*' => function($x, $y) { return $x * $y; },
                '/' => function($x, $y) { return $x / $y; }
            ];
        }
        if (!isset($cache[$operator])) {
            throw new \InvalidArgumentException("Not defined for {$operator}");
        }
        return $cache[$operator];
    }

    /**
     * @param $array
     * @param Closure $closure
     * @return mixed
     */
    static function find($array, Closure $closure) {
        return parent::find($array, $closure);
    }

    static function initial($array, $to = 1) {
        // underscore.php returns x for initial([x]) and that's quite a surprise
        if (count($array) === 1 && $to >= 1) {
            return [];
        }
        return parent::initial($array, $to);
    }
}

class Nullable {
    static public function get($nullable, $default) {
        return $nullable === null ? $default : $nullable;
    }

    static public function map($nullable, $f) {
        return $nullable !== null ? $f($nullable) : $nullable;
    }

    /**
     * @param $nullable
     * @return \Iterator
     */
    static function iter($nullable) {
        return new ArrayIterator($nullable === null ? [] : [$nullable]);
    }
}

class Strings extends StringsMethods {
    static function isEmpty($str) {
        return $str === null || trim($str) === '';
    }

    static function ifEmpty($str, $value) {
        return self::isEmpty($str) ? $value : $str;
    }

    static function contains($s, $subString) {
        return strpos($s, $subString) !== false;
    }

    static function capitalize($s, $lowerRest = true) {
        $xformRest = $lowerRest ? _::func([self::class, 'lower']) : _::identity();
        return self::upper(mb_substr($s, 0, 1)) . $xformRest(mb_substr($s, 1));
    }
}

// TODO warn when used, so it won't be left in production
/** useful for interactive development, repl */
trait DynamicMethods {
    static $_instance = [];
    static $_static = [];

    function __call($name, $arguments) {
        if (isset(self::$_instance[$name])) {
            $f = \Closure::bind(self::$_instance[$name], $this, __CLASS__);
            return $f(...$arguments);
        } else {
            throw new \BadMethodCallException("Call to undefined instance method '{$name}'");
        }
    }

    static function __callStatic($name, $arguments) {
        if (isset(self::$_static[$name])) {
            $f = \Closure::bind(self::$_static[$name], null, __CLASS__);
            return $f(...$arguments);
        } else {
            throw new \BadMethodCallException("Call to undefined static method '{$name}'");
        }
    }
}

class Env {
    const DEV = 'dev';
    const PROD = 'prod';
    const TEST = 'test';
}

class App {
    private static $instance;

    static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    static function env() {
        $app = Nullable::get(Configuration::getValue('app'), []);
        return _::get($app, 'env', Env::PROD);
    }

    static function useBitrixAsset() {
        // use bitrix asset pipeline for non-dev environments
        return self::env() !== Env::DEV;
    }
}

class View {
    static function asset($path) {
        return SITE_TEMPLATE_PATH.'/build/assets/'.$path;
    }

    static function resize($imageFileArray, $width, $height, $type = BX_RESIZE_IMAGE_PROPORTIONAL) {
        $resized = CFile::ResizeImageGet($imageFileArray, [
            'width' => $width,
            'height' => $height
        ], $type);
        return $resized['src'];
    }

    static function template($path) {
        return SITE_TEMPLATE_PATH.'/'.$path;
    }

    static function path($path) {
        // TODO ad-hoc
        if ($path === '/') return SITE_DIR;
        return SITE_DIR.$path.'/';
    }

    static function includedArea($path) {
        return SITE_DIR.'include/'.$path;
    }

    static function isEmpty($x) {
        return
            $x === null
            || $x === false
            || (is_array($x) && _::isEmpty($x))
            || (is_string($x) && Strings::isEmpty($x));
    }
    
    static function upper($str) {
        return Strings::upper($str);
    }

    static function lower($str) {
        return Strings::lower($str);
    }

    static function capitalize($str) {
        return Strings::capitalize($str);
    }

    static function appendToView($view, $content) {
        global $APPLICATION;
        $APPLICATION->AddViewContent($view, $APPLICATION->GetViewContent($view).$content);
    }

    static function attrs($map) {
        return join(' ', _::map($map, function($value, $key) {
            return $key.'="'.htmlspecialchars($value).'"';
        }));
    }

    static function get($collection, $key, $default = null) {
        return _::get($collection, $key, $default);
    }

    static function render($absPath, $data) {
        extract($data);
        if (!file_exists($absPath)) {
            throw new \Exception("The template could not be found at {$absPath}.");
        }
        try {
            $level = ob_get_level();
            ob_start();
            include $absPath;
            return ob_get_clean();
        } catch (\Throwable $e) {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }
            throw $e;
        } catch (\Exception $e) {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }
            throw $e;
        }
    }

    static function showForLayout($layout, $showFn) {
        global $APPLICATION;
        $APPLICATION->AddBufferContent(function () use ($layout, $showFn, &$APPLICATION) {
            if ($APPLICATION->GetProperty('layout', 'default') === $layout) {
                ob_start();
                $showFn();
                return ob_get_clean();
            } else {
                return '';
            }
        });
    }
}

trait NewsListLike {
    private static function elementEditingLinks($element) {
        assert(isset($element['IBLOCK_ID']));
        assert(isset($element['ID']));
        // from news.list
        $arButtons = \CIBlock::GetPanelButtons(
            $element["IBLOCK_ID"],
            $element["ID"],
            0,
            array("SECTION_BUTTONS"=>false, "SESSID"=>false)
        );
        return [
            "EDIT_LINK" => $arButtons["edit"]["edit_element"]["ACTION_URL"],
            "DELETE_LINK" => $arButtons["edit"]["delete_element"]["ACTION_URL"]
        ];
    }

    private static function sectionEditingLinks($section) {
        assert(isset($section['IBLOCK_ID']));
        assert(isset($section['ID']));
        // from catalog.section.list
        $arButtons = \CIBlock::GetPanelButtons(
            $section["IBLOCK_ID"],
            0,
            $section["ID"],
            array("SESSID"=>false, "CATALOG"=>true)
        );
        return [
            "EDIT_LINK" => $arButtons["edit"]["edit_section"]["ACTION_URL"],
            "DELETE_LINK" => $arButtons["edit"]["delete_section"]["ACTION_URL"]
        ];
    }
    /**
     * @param array $el
     * @param CBitrixComponentTemplate $template
     * @return string dom element id
     */
    static function addEditingActions($el, $template, $type = 'element') {
        $isSection = $type === 'section' || isset($el['DEPTH_LEVEL']);
        if (!_::isEmpty(array_diff(['EDIT_LINK', 'DELETE_LINK'] , array_keys($el)))) {
            $links = $isSection ? self::sectionEditingLinks($el) : self::elementEditingLinks($el);
            $el = array_merge($el, $links);
        }
        $template->AddEditAction($el['ID'], $el['EDIT_LINK'],
            CIBlock::GetArrayByID($el['IBLOCK_ID'], $isSection ? 'SECTION_EDIT' : 'ELEMENT_EDIT'));
        $template->AddDeleteAction($el['ID'], $el['DELETE_LINK'],
            CIBlock::GetArrayByID($el['IBLOCK_ID'], $isSection ? 'SECTION_DELETE' : 'ELEMENT_DELETE'),
            ['CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
        return $template->GetEditAreaId($el['ID']);
    }
}

