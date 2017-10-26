<?
use App\App;
use App\View as v;

// TODO refactor: layout context dependency
$checkoutLink = v::get(App::getInstance()->layoutContext(), 'catalog.checkoutLink');
?>
<div class="modal" id="product-added-to-cart">
    <div class="block">
        <span class="close close-modal">×</span>
        <p class="title">Товар добавлен в корзину</p>
        <button class="close-modal download-btn">Продолжить покупки</button>
        <a class="download-btn" href="<?= $checkoutLink ?>">Перейти к оформлению</a>
    </div>
</div>
