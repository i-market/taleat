<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
use App\Product;

$showParent = function($section, $fragment) {
    ?>
    <? if ($fragment === 'header'): ?>
        <div class="accordeon-item">
            <div class="accordeon-wrap-link">
                <a href="<?= Product::sectionUrl($section) ?>" class="accordeon-link"><?= $section['NAME'] ?></a>
    <? elseif ($fragment === 'footer'): ?>
            </div>
        </div>
    <? endif ?>
    <?
};
$showChildrenWrap = function($fragment, $isActive = false) {
    ?>
    <? if ($fragment === 'header'): ?>
    <span class="accordeon-title <?= $isActive ? 'active' : '' ?>"></span>
    <div class="accordeon-inner" <?= $isActive ? 'style="display: block"' : '' ?>>
        <ul>
    <? elseif ($fragment === 'footer'): ?>
        </ul>
    </div>
    <? endif ?>
    <?
};
$showChild = function($section) {
    ?>
    <li><a href="<?= Product::sectionUrl($section) ?>"><?= $section['NAME'] ?></a></li>
    <?
};
?>
<div class="content-menu <?= v::get($arParams, 'CLASS', '') ?>">
    <span class="catalog-menu-close"></span>
    <div class="accordeon">
        <?
        $prevDepth = 1;
        $isPrevActive = false;
        foreach ($arResult['SECTIONS'] as $idx => $section) {
            if ($section['DEPTH_LEVEL'] == 1) {
                if ($idx !== 0) {
                    // close previous section tags
                    if ($prevDepth == 2) {
                        $showChildrenWrap('footer');
                    }
                    $showParent([], 'footer');
                }
                $showParent($section, 'header');
                $isPrevActive = Product::pathStartsWith(Product::sectionUrl($section), $APPLICATION->GetCurDir());
            } elseif ($section['DEPTH_LEVEL'] == 2) {
                if ($prevDepth == 1) {
                    $showChildrenWrap('header', $isPrevActive);
                    $isPrevActive = false;
                }
                $showChild($section);
            }
            if ($idx === count($arResult['SECTIONS']) - 1) {
                // last section
                if ($section['DEPTH_LEVEL'] == 2) {
                    $showChildrenWrap('footer');
                }
                $showParent([], 'footer');
            }
            $prevDepth = $section['DEPTH_LEVEL'];
        }
        ?>
    </div>
</div>
