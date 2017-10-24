<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<? $link = $arResult['PROPERTIES']['LINK']['VALUE'] ?>
<? $phoneImg = $arResult['DISPLAY_PROPERTIES']['PHONE']['FILE_VALUE'] ?>
<? list($tag, $attrs) = !v::isEmpty($link) ? ['a', 'href="'.$link.'"'] : ['div', ''] ?>
<section class="banner">
    <div class="wrap">
        <<?= $tag ?> <?= $attrs ?> class="desktop">
            <img src="<?= $arResult['PREVIEW_PICTURE']['SRC'] ?>" alt="<?= $arResult['PREVIEW_PICTURE']['ALT'] ?>">
        </<?= $tag ?>>
        <? if (!v::isEmpty($phoneImg)): ?>
            <<?= $tag ?> <?= $attrs ?> class="phone">
                <img src="<?= $phoneImg['SRC'] ?>" alt="<?= $phoneImg['ALT'] ?>">
            </<?= $tag ?>>
        <? endif ?>
    </div>
</section>