<?php

namespace App\View;

use App\View as v;

class FormMacros {
    static function showInput($name, $label, $_opts = []) {
        $opts = array_merge(['type' => 'text', 'required' => false], $_opts);
        ?>
        <input name="<?= $name ?>"
               value="<?= v::escAttr($_REQUEST[$name]) ?>"
               class="input"
               type="<?= $opts['type'] ?>"
               <?= $opts['required'] ? 'required' : ''  ?>
               placeholder="<?= $label ?>">
        <?
    }
}