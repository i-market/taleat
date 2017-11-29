<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<li class="footer-links">
    <? foreach ($arResult as $item): ?>
        <? $class = v::get($item, 'PARAMS.class', '') . ($item['SELECTED'] ? ' active' : '') ?>
        <p><a class="<?= $class ?>" href="<?= $item['LINK'] ?>"><?= $item['TEXT'] ?></a></p>
    <? endforeach ?>
</li>
