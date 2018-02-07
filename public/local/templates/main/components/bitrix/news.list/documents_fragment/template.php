<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
use Core\Util;
?>
<div class="desc">
    <? foreach ($arResult['SECTIONS'] as $section): ?>
        <? if (!v::isEmpty($section['DESCRIPTION'])): ?>
            <div class="editable-area desc__item" id="<?= v::addEditingActions($section, $this) ?>">
                <? if (count($arResult['SECTIONS']) > 1): ?>
                    <h3><?= $section['NAME'] ?></h3>
                <? endif ?>
                <?= $section['DESCRIPTION'] ?>
            </div>
        <? endif ?>
    <? endforeach ?>
</div>
<div class="wrap-documents">
    <? foreach ($arResult['ITEMS'] as $item): ?>
        <? $section = v::get($arResult['SECTIONS'], $item['IBLOCK_SECTION_ID']) ?>
        <?
        // TODO refactor item filter
        if ($item['IBLOCK_SECTION_ID'] && !$section) {
            continue;
        }
        ?>
        <? $path = $item['DISPLAY_PROPERTIES']['DOCUMENT']['FILE_VALUE']['SRC'] ?>
        <? list($_, $ext) = Util::splitFileExtension($path) ?>
        <div class="item" id="<?= v::addEditingActions($item, $this) ?>">
            <a href="<?= $path ?>" <?= v::attrs(v::docLinkAttrs($ext)) ?> class="item-link <?= v::lower($ext) ?>">
                <span class="size"><?= v::fileSize($path) ?></span>
                <span class="name"><?= $item['NAME'] ?></span>
            </a>
            <? if (!v::isEmpty($section)): ?>
                <a class="brand"
                   href="<?= '?SECTION_ID='.$section['ID'] ?>"
                   data-id="<?= $section['ID'] ?>"><?= $section['NAME'] ?></a>
            <? endif ?>
        </div>
    <? endforeach ?>
</div>
<?= $arResult['NAV_STRING'] ?>
