<?php

namespace Core;

use CDBResult;
use CIBlockResult;

class Iblock {
    // TODO not iblock specific
    static function iter(CDBResult $result) {
        while($x = $result->GetNext()) {
            yield $x;
        }
    }

    static function iterElements(CIBlockResult $result) {
        while($x = $result->GetNextElement()) {
            yield array_merge($x->GetFields(), [
                'PROPERTIES' => $x->GetProperties()
            ]);
        }
    }
}