<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<section class="safety section">
    <div class="wrap">
        <div class="wrap-min">
            <div class="safety-title">
                <span class="date"><?= $arResult['DISPLAY_ACTIVE_FROM'] ?></span>
                <h2><?= $arResult['NAME'] ?></h2>
                <a class="back" href="<?= $arResult['LIST_PAGE_URL'] ?>">Назад</a>
            </div>
            <div class="editable-area default-page">
                <? if (!v::isEmpty($arResult['DETAIL_PICTURE'])): ?>
                    <figure class="img">
                        <div class="img">
                            <img src="<?= $arResult['DETAIL_PICTURE']['SRC'] ?>" alt="<?= $arResult['DETAIL_PICTURE']['ALT'] ?>">
                        </div>
                        <figcaption><?= $arResult['DETAIL_PICTURE']['DESCRIPTION'] ?></figcaption>
                    </figure>
                <? endif ?>
                <?= $arResult['DETAIL_TEXT'] ?>
            </div>
        </div>
    </div>
</section>