<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); 

use App\View as v;
?>
<div class="wrap-useful-slider">
    <div class="useful-slider">
        <? foreach ($arResult['ITEMS'] as $item): ?>
            <div class="slide">
                <div class="img">
                    <img src="<?= v::resize($item['PREVIEW_PICTURE'], 350, 350) ?>" alt="<?= $item['PREVIEW_PICTURE']['ALT'] ?>">
                </div>
                <div class="info">
                    <a href="<?= $item['DETAIL_PAGE_URL'] ?>"><p class="title"><?= $item['NAME'] ?></p></a>
                    <div class="editable-area paragraph">
                        <?= $item['PREVIEW_TEXT'] ?>
                    </div>
                    <p class="more"><a href="<?= $item['DETAIL_PAGE_URL'] ?>">Подробнее...</a></p>
                </div>
            </div>
        <? endforeach ?>
    </div>
    <div class="dots"></div>
</div>
