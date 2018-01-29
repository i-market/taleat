<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<? foreach ($arResult['ITEMS'] as $item): ?>
    <div class="useful-item" id="<?= v::addEditingActions($item, $this) ?>">
        <a href="<?= $item['DETAIL_PAGE_URL'] ?>"><p class="title"><?= $item['NAME'] ?></p></a>
        <div class="editable-area paragraph">
            <?= $item['PREVIEW_TEXT'] ?>
        </div>
        <p class="more"><a href="<?= $item['DETAIL_PAGE_URL'] ?>">Подробнее...</a></p>
    </div>
<? endforeach ?>
