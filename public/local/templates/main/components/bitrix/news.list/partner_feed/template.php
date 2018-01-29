<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<div class="helpful-information">
    <? foreach ($arResult['ITEMS'] as $item): ?>
        <div class="item" id="<?= v::addEditingActions($item, $this) ?>">
            <p class="top">
                <? $date = $item['DISPLAY_ACTIVE_FROM']
                    ?: DateTime::createFromFormat('Y.m.d', $item['CREATED_DATE'])->format($arParams['ACTIVE_DATE_FORMAT']) ?>
                <span class="date"><?= $date ?></span>
                <? if (!v::isEmpty($item['SECTION'])): ?>
                    <a class="brand"
                       href="<?= '?SECTION_ID='.$item['SECTION']['ID'] ?>"
                       data-id="<?= $item['SECTION']['ID'] ?>"><?= $item['SECTION']['NAME'] ?></a>
                <? endif ?>
            </p>
            <p class="text"><a href="<?= $item['DETAIL_PAGE_URL'] ?>"><?= $item['NAME'] ?></a></p>
        </div>
    <? endforeach ?>
</div>
<?= $arResult['NAV_STRING'] ?>

