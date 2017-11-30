<?php

namespace App\View;

use App\View as v;
use Core\Underscore as _;
use Core\Util;

class FormMacros {
    static function showInput($name, $label, $_opts = []) {
        $opts = array_merge(['type' => 'text', 'required' => false], $_opts);
        $path = Util::formInputNamePath($name);
        ?>
        <input name="<?= $name ?>"
               value="<?= v::escAttr(_::get($_REQUEST, $path)) ?>"
               class="input"
               type="<?= $opts['type'] ?>"
               <?= $opts['required'] ? 'required' : ''  ?>
               placeholder="<?= $label ?>">
        <?
    }
}