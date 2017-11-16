<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
use App\Catalog;
?>

<? // TODO read legacy template closely ?>

<? if (!v::isEmpty(v::get($arResult, 'NAME'))): ?>
    <div class="catalog-title">
        <h2><?= $arResult['NAME'] ?></h2>
        <?= v::render('partials/catalog/title_logo.php', [
            'picture' => $arResult['BRAND']['PICTURE']
        ]) ?>
    </div>
<? endif ?>
<div class="sort-block">
    <div class="left">
        <span class="text">Сортировать:</span>
        <select name="sort" class="sort">
            <?
            $opts = [
                'price:asc' => 'по возрастанию цены',
                'price:desc' => 'по убыванию цены',
                'show_counter:desc' => 'по популярности',
                'created:desc' => 'по дате добавления',
            ]
            ?>
            <? foreach ($opts as $value => $text): ?>
                <? $selected = $arParams['STATE']['params']['sort'] === $value ?>
                <option value="<?= $value ?>" <?= $selected ? 'selected' : '' ?>><?= $text ?></option>
            <? endforeach ?>
        </select>
    </div>
    <div class="right">
        <span class="text">Показывать по:</span>
        <select name="per_page" class="per-page">
            <? foreach (Catalog::$perPageOpts as $value => $count): ?>
                <? $selected = $count == $arParams['PAGE_ELEMENT_COUNT'] ?>
                <option value="<?= $value ?>" <?= $selected ? 'selected' : '' ?>><?= $value === 'all' ? 'ВСЕ' : $count ?></option>
            <? endforeach ?>
        </select>
    </div>
</div>
<div class="grid catalo-items-grid">
    <? foreach ($arResult['ITEMS'] as $item): ?>
        <?= v::render('partials/catalog/product_card.php', [
            'item' => $item,
            'class' => 'col col-3',
        ]) ?>
    <? endforeach ?>
</div>

<? $this->SetViewTarget($arParams['PAGINATOR_VIEW']) ?>
<?= $arResult['NAV_STRING'] ?>
<? $this->EndViewTarget() ?>
