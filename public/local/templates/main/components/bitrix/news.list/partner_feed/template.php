<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<div class="helpful-information">
    <? foreach ($arResult['ITEMS'] as $item): ?>
        <div class="item">
            <p class="top">
                <? $date = $item['DISPLAY_ACTIVE_FROM']
                    ?: DateTime::createFromFormat('Y.m.d', $item['CREATED_DATE'])->format($arParams['ACTIVE_DATE_FORMAT']) ?>
                <span class="date"><?= $date ?></span>
                <? if (!v::isEmpty($item['SECTION'])): ?>
                    <a href="javascript:void(0)" data-id="<?= $item['SECTION']['ID'] ?>" class="brand"><?= $item['SECTION']['NAME'] ?></a>
                <? endif ?>
            </p>
            <? // TODO detail page ?>
            <p class="TODO-mockup text"><a href="<?= $item['DETAIL_PAGE_URL'] ?>"><?= $item['NAME'] ?></a></p>
        </div>
    <? endforeach ?>
</div>
<?= $arResult['NAV_STRING'] ?>

