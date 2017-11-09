<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); 

use App\View as v;
use App\Product;
?>
<div class="catalog-title">
    <h2><?= $arResult['SECTION']['NAME'] ?></h2>
    <? // TODO brand image ?>
    <div class="TODO-mockup catalog-title-img">
        <img src="<?= v::asset('images/pic/labels/2.png') ?>" alt="">
    </div>
</div>
<div class="ctalog-inner">
    <div class="grid">
        <? foreach ($arResult['SECTIONS'] as $section): ?>
            <a href="<?= Product::sectionUrl($section) ?>" class="col col-3 ctalog-item-box">
                <div class="img">
                    <? if (!v::isEmpty($section['PICTURE'])): ?>
                        <div style="background-image: url('<?= v::resize($section['PICTURE'], ...Product::IMAGE_MEDIUM) ?>')"
                             class="img-inner"
                             title="<?= $section['NAME'] ?>"></div>
                    <? else: ?>
                        <div class="no-img-placeholder"><div class="content"></div></div>
                    <? endif ?>
                </div>
                <p class="title"><?= $section['NAME'] ?></p>
            </a>
        <? endforeach ?>
    </div>
</div>
