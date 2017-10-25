<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
?>
<? foreach ($arResult['ITEMS'] as $item): ?>
    <div class="useful-item">
        <p class="title"><?= $item['NAME'] ?></p>
        <div class="editable-area paragraph">
            <?= $item['PREVIEW_TEXT'] ?>
        </div>
        <p class="more"><a href="<?= $item['DETAIL_PAGE_URL'] ?>">Подробнее...</a></p>
    </div>
<? endforeach ?>
