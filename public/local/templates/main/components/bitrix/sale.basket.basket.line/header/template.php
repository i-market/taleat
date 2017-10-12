<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
use Core\Util;
?>
<a class="header-cart" href="<?= v::path('personal/cart') ?>">
    <span class="header-cart-ico"></span>
    <? if ($arResult['NUM_PRODUCTS'] > 0): ?>
        <?= $arResult['NUM_PRODUCTS'].' '.Util::units($arResult['NUM_PRODUCTS'], 'товар', 'товара' , 'товаров') ?>
    <? else: ?>
        Ваша корзина пуста
    <? endif ?>
</a>
