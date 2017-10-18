<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
use Core\Util;
?>
<a class="header-cart <?= $arResult['NUM_PRODUCTS'] > 0 ? 'goods-inside' : '' ?>"
   href="<?= v::path('personal/cart') ?>">
    <span class="header-cart-ico"></span>
    <? if ($arResult['NUM_PRODUCTS'] > 0): ?>
        <? $units = Util::units($arResult['NUM_PRODUCTS'], 'товар', 'товара' , 'товаров') ?>
        <?= join(' ', [$arResult['NUM_PRODUCTS'], $units, 'на', $arResult['TOTAL_PRICE']]) ?>
    <? else: ?>
        Ваша корзина пуста
    <? endif ?>
</a>
