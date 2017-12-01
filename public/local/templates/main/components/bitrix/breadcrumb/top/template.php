<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
use Core\Underscore as _;

$showItemName = function($title) {
    $xform = ini_get('mbstring.func_overload') == 2
        ? _::partialRight('TruncateText', 50)
        : _::identity();
    ?>
    <span itemprop="name"><?= $xform($title) ?></span>
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
