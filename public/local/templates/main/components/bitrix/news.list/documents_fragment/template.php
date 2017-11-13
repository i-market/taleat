<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
use Core\Util;
?>
<div class="wrap-documents">
    <? foreach ($arResult['ITEMS'] as $item): ?>
        <? $path = $item['DISPLAY_PROPERTIES']['DOCUMENT']['FILE_VALUE']['SRC'] ?>
        <? list($_, $ext) = Util::splitFileExtension($path) ?>
        <div class="item">
            <a href="<?= $path ?>" <?= v::attrs(v::docLinkAttrs($ext)) ?> class="item-link <?= v::lower($ext) ?>">
                <span class="size"><?= v::fileSize($path) ?></span>
                <span class="name"><?= $item['NAME'] ?></span>
            </a>
            <? // TODO brand ?>
            <a class="TODO-mockup brand" href="#">Braun</a>
        </div>
    <? endforeach ?>
</div>
<?= $arResult['NAV_STRING'] ?>

