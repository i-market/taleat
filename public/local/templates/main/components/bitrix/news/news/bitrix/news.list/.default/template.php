<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<section class="news news--pages section">
    <div class="section-title">
        <div class="wrap">
            <div class="section-title-block">
                <h2><? $APPLICATION->ShowTitle(false) ?></h2>
            </div>
        </div>
    </div>
    <div class="wrap border">
        <div class="grid">
            <? foreach ($arResult['ITEMS'] as $idx => $item): ?>
                <? $hasPic = is_array($item['PREVIEW_PICTURE']) ?>
                <div class="col col-2">
                    <? if ($idx === 0): ?>
                        <div class="first-new <?= !$hasPic ? 'first-new--without-img' : '' ?>">
                            <? if ($hasPic): ?>
                                <div class="img">
                                    <img src="<?= $item['PREVIEW_PICTURE']['SRC'] ?>" alt="<?= $item['PREVIEW_PICTURE']['ALT'] ?>">
                                </div>
                            <? endif ?>
                            <div class="info">
                                <a href="<?= $item['DETAIL_PAGE_URL'] ?>"><p class="title"><?= $item['NAME'] ?></p></a>
                                <p class="editable-area text"><?= $item['PREVIEW_TEXT'] ?></p>
                                <p class="more"><a href="<?= $item['DETAIL_PAGE_URL'] ?>">читать новость</a></p>
                            </div>
                        </div>
                    <? else: ?>
                        <a href="<?= $item['DETAIL_PAGE_URL'] ?>" class="news-item">
                            <div class="img">
                                <? if ($hasPic): ?>
                                    <img src="<?= $item['PREVIEW_PICTURE']['SRC'] ?>" alt="<?= $item['PREVIEW_PICTURE']['ALT'] ?>">
                                <? endif ?>
                            </div>
                            <div class="info">
                                <p class="date"><?= $item['DISPLAY_ACTIVE_FROM'] ?></p>
                                <p class="title"><?= $item['NAME'] ?></p>
                                <div class="editable-area text"><?= $item['PREVIEW_TEXT'] ?></div>
                            </div>
                        </a>
                    <? endif ?>
                </div>
            <? endforeach ?>
        </div>
    </div>
</section>
<section>
    <?= $arResult['NAV_STRING'] ?>
</section>
