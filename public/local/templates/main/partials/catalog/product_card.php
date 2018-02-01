<?
use App\View as v;
use App\Product;
?>
<? $thumbnail = Product::thumbnail($item) ?>
<? $price = Product::basePrice($item['ID']) ?>
<a href="<?= Product::elementUrl($item) ?>"
   class="item-box <?= isset($class) ? $class : '' ?>"
   id="<?= isset($id) ? $id : '' ?>">
    <?
    $attrs = !v::isEmpty($thumbnail)
        ? [
            'class' => 'img',
            'style' => "background-image: url('".v::resize($thumbnail, ...Product::IMAGE_MEDIUM_SMALL)."')",
            'title' => v::get($thumbnail, 'ALT', '')
        ]
        : ['class' => 'img no-img']
    ?>
    <div <?= v::attrs($attrs) ?>></div>
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
            <span class="cart buy-button" data-id="<?= $item['ID'] ?>" role="button" title="Добавить в корзину"></span>
        </div>
    </div>
</a>
