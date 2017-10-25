<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); 

use App\View as v;
use App\Product;
?>
<div class="catalog-title">
    <h2><?= $arResult['NAME'] ?></h2>
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
                    <img src="<?= v::resize($section['PICTURE'], 270, 200, BX_RESIZE_IMAGE_EXACT) ?>" alt="<?= $section['NAME'] ?>">
                </div>
                <p class="title"><?= $section['NAME'] ?></p>
            </a>
        <? endforeach ?>
    </div>
</div>
