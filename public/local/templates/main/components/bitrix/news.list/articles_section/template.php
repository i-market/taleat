<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<? if (!v::isEmpty($arResult['ITEMS'])): ?>
    <section class="useful section hidden">
        <div class="section-title">
            <div class="wrap">
                <div class="section-title-block">
                    <h2>Полезное</h2>
                    <div class="section-title-link">|<a href="<?= v::path('articles') ?>">все статьи</a></div>
                </div>
            </div>
        </div>
        <div class="wrap useful-block">
            <div class="left">
                <div class="wrap-useful-slider">
                    <div class="useful-slider">
                        <? foreach ($arResult['SLIDER'] as $item): ?>
                            <div class="slide">
                                <div class="img">
                                    <img src="<?= v::resize($item['PREVIEW_PICTURE'], 350, 350) ?>" alt="<?= $item['PREVIEW_PICTURE']['ALT'] ?>">
                                </div>
                                <div class="info">
                                    <p class="title"><?= $item['NAME'] ?></p>
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
            </div>
            <div class="right">
                <? foreach ($arResult['REST'] as $item): ?>
                    <div class="useful-item">
                        <p class="title"><?= $item['NAME'] ?></p>
                        <div class="editable-area paragraph">
                            <?= $item['PREVIEW_TEXT'] ?>
                        </div>
                        <p class="more"><a href="<?= $item['DETAIL_PAGE_URL'] ?>">Подробнее...</a></p>
                    </div>
                <? endforeach ?>
            </div>
        </div>
    </section>
<? endif ?>