<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<? if (!v::isEmpty($arResult['ITEMS'])): ?>
    <section class="news section">
        <div class="section-title">
            <div class="wrap">
                <div class="section-title-block">
                    <h2>Новости</h2>
                    <div class="section-title-link">|<a href="<?= v::path('news') ?>">все <span class="hidden">новости</span></a></div>
                </div>
            </div>
        </div>
        <div class="wrap">
            <div class="grid">
                <? foreach ($arResult['ITEMS'] as $item): ?>
                    <div class="col col-2" id="<?= v::addEditingActions($item, $this) ?>">
                        <a href="<?= $item['DETAIL_PAGE_URL'] ?>" class="news-item">
                            <? if (is_array($item['PREVIEW_PICTURE'])): ?>
                                <div class="img">
                                    <img src="<?= $item['PREVIEW_PICTURE']['SRC'] ?>" alt="<?= $item['PREVIEW_PICTURE']['ALT'] ?>">
                                </div>
                            <? endif ?>
                            <div class="info">
                                <p class="date"><?= $item['DISPLAY_ACTIVE_FROM'] ?></p>
                                <p class="title"><?= $item['NAME'] ?></p>
                                <div class="editable-area text">
                                    <?= $item['PREVIEW_TEXT'] ?>
                                </div>
                            </div>
                        </a>
                    </div>
                <? endforeach ?>
            </div>
        </div>
    </section>
<? endif ?>