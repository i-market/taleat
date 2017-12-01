<?
use App\View as v;
?>
<div class="modal" id="product-added-to-cart">
    <div class="block">
        <span class="close close-modal">×</span>
        <p class="title">Товар добавлен в корзину</p>
        <button class="close-modal download-btn">Продолжить покупки</button>
        <a class="download-btn" href="<?= v::path('personal/cart') ?>">Перейти в корзину</a>
    </div>
</div>
