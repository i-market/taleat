<?php

namespace App\View;

class FormMacros {
    static function showInput($name, $label, $_opts = []) {
        $opts = array_merge(['type' => 'text', 'required' => false], $_opts);
        ?>
        <input name="<?= $name ?>"
               value="<?= $_REQUEST[$name] ?>"
               class="input"
               type="<?= $opts['type'] ?>"
               <?= $opts['required'] ? 'required' : ''  ?>
               placeholder="<?= $label ?>">
        <?
    }
}