<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;

$showItemName = function($title) {
    ?>
    <span itemprop="name"><?= TruncateText($title, 50) ?></span>
    <?
};
$showItem = function($position, $link, $title, $isLast) use ($showItemName) {
    ?>
    <li class="bread-crumbs-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a class="link" href="<?= $link ?>" itemprop="item">
            <? $showItemName($title) ?>
        </a>
        <meta itemprop="position" content="<?= $position ?>" />
    </li>
    <?
};
?>
<? ob_start() ?>
<? if (!v::isEmpty($arResult)): ?>
    <ol class="bread-crumbs" itemscope itemtype="http://schema.org/BreadcrumbList">
        <? foreach ($arResult as $idx => $item): ?>
            <? $position = $idx + 1 ?>
            <? $isLast = $idx + 1 === count($arResult) ?>
            <? $showItem($position, $item['LINK'], $item['TITLE'], $isLast) ?>
        <? endforeach ?>
    </ol>
<? endif ?>
<? return ob_get_clean() ?>
