<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\Product;
use App\View as v;
use Core\Util;
?>
<div class="product-registration-sticky">
    <table>
        <? foreach ($arResult['BASKET_ITEMS'] as $idx => $item): ?>
            <? $formatPrice = function ($price) use ($item) {
                return CCurrencyLang::CurrencyFormat($price, $item['CURRENCY'], true);
            } ?>
            <tr>
                <td><?= $idx + 1 ?></td>
                <td><?= $item['NAME'] ?></td>
                <td><?= $item['QUANTITY'].' x '.$formatPrice($item['PRICE']) ?></td>
                <td><?= $formatPrice($item['PRICE'] * $item['QUANTITY']) ?></td>
            </tr>
        <? endforeach ?>
    </table>
    <div class="total">
        <? $quantity = count($arResult['BASKET_ITEMS']) ?>
        <p class="text">
            Итого: <?= $quantity.' '.Util::units($quantity, 'товар', 'товара', 'товаров') ?>, на сумму
            <span class="price"><?= Product::wrapCurrency($arResult['ORDER_PRICE_FORMATED']) ?></span>
        </p>
    </div>
    <a href="<?= v::path('personal/cart') ?>" class="simple-btn">Редактировать заказ</a>
</div>
