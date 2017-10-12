<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;

$showParent = function($section, $fragment) {
    $href = fn_get_chainpath($section['IBLOCK_ID'], $section['ID']);
    ?>
    <? if ($fragment === 'header'): ?>
        <div class="accordeon-item" id="<?= v::addEditingActions($section, $this) ?>">
            <div class="accordeon-wrap-link">
                <a href="<?= $href ?>" class="accordeon-link"><?= $section['NAME'] ?></a>
    <? elseif ($fragment === 'footer'): ?>
            </div>
        </div>
    <? endif ?>
    <?
};
$showChildrenWrap = function($fragment) {
    ?>
    <? if ($fragment === 'header'): ?>
    <span class="accordeon-title"></span>
    <div class="accordeon-inner">
        <ul>
    <? elseif ($fragment === 'footer'): ?>
        </ul>
    </div>
    <? endif ?>
    <?
};
$showChild = function($section) {
    $href = fn_get_chainpath($section['IBLOCK_ID'], $section['ID']);
    ?>
    <li><a href="<?= $href ?>"><?= $section['NAME'] ?></a></li>
    <?
};
?>

<div class="content-menu">
    <span class="catalog-menu-close"></span>
    <div class="accordeon">
        <?
        // TODO refactor
        $prevDepth = 1;
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
            } elseif ($section['DEPTH_LEVEL'] == 2) {
                if ($prevDepth == 1) {
                    $showChildrenWrap('header');
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
