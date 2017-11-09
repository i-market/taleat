<?php

namespace App\View;

class FormMacros {
    static function showInput($name, $label, $_opts = []) {
        $opts = array_merge(['type' => 'text'], $_opts);
        ?>
        <input name="<?= $name ?>"
               value="<?= $_REQUEST[$name] ?>"
               class="input"
               type="<?= $opts['type'] ?>"
               placeholder="<?= $label ?>">
        <?
    }
}