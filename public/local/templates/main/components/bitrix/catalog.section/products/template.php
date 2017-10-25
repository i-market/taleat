<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
use App\App;

App::getInstance()->assert($arParams["SECTION_ID"]>0, 'legacy')
?>

<? // TODO close read of the legacy template ?>

<div class="catalog-title">
    <h2><?= $arResult['NAME'] ?></h2>
    <? // TODO brand image ?>
    <div class="TODO-mockup catalog-title-img">
        <img src="<?= v::asset('images/pic/labels/2.png') ?>" alt="">
    </div>
</div>
<? // TODO filter ?>
<div class="TODO-mockup sort-block">
    <div class="left">
        <span class="text">Сортировать:</span>
        <select name="" id="">
            <option value="">по возрастанию цены</option>
            <option value="">по убыванию цены</option>
            <option value="">по популярности</option>
            <option value="">по дате добавления</option>
        </select>
    </div>
    <div class="right">
        <span class="text">Показывать по:</span>
        <select name="" id="">
            <option value="">12</option>
            <option value="">24</option>
            <option value="">50</option>
            <option value="">ВСЕ</option>
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
<? // TODO paginator ?>
<div class="TODO-mockup">
<?= $arResult['NAV_STRING'] ?>
</div>
<? $this->EndViewTarget() ?>
