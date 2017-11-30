<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<ul>
    <? foreach ($arResult as $item): ?>
        <? $class = v::get($item, 'PARAMS.class', '') . ($item['SELECTED'] ? ' active' : '') ?>
        <li><a class="<?= $class ?>" href="<?= $item['LINK'] ?>"><span><?= $item['TEXT'] ?></span></a></li>
    <? endforeach ?>
</ul>
