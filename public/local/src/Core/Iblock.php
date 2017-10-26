<?php

namespace Core;

use CIBlockResult;

class Iblock {
    static function collect(CIBlockResult $result) {
        while($x = $result->GetNext()) {
            yield $x;
        }
    }

    static function collectElements(CIBlockResult $result) {
        while($x = $result->GetNextElement()) {
            yield array_merge($x->GetFields(), [
                'PROPERTIES' => $x->GetProperties()
            ]);
        }
    }
}