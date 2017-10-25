<?
use App\View as v;
use App\Product;
?>
<? $thumbnail = Product::thumbnail($item) ?>
<? $price = Product::basePrice($item['ID']) ?>
<a href="<?= Product::elementUrl($item) ?>" class="item-box <?= isset($class) ? $class : '' ?>">
    <div class="img <?= v::isEmpty($thumbnail) ? 'no-img' : '' ?>">
        <? if (!v::isEmpty($thumbnail)): ?>
            <img src="<?= $thumbnail['SRC'] ?>" alt="<?= $thumbnail['ALT'] ?>">
        <? endif ?>
    </div>
    <div class="info">
        <p class="label-unfo">
            <span class="articul"><?= $item['PROPERTIES']['ARTNUMBER']['VALUE'] ?></span>
            <span class="label-name"><?= Product::brand($item) ?></span>
        </p>
        <p class="name"><?= $item['NAME'] ?></p>
        <div class="price-info">
            <? // TODO (span (span price) currency) ?>
            <? $formatted = CCurrencyLang::CurrencyFormat($price['PRICE'], $price['CURRENCY'], true) ?>
            <span class="price"><span><?= $formatted ?></span></span>
            <? // TODO clickable cart? ?>
            <span class="cart"></span>
        </div>
    </div>
</a>
