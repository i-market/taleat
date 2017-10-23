<?
use App\App;
use App\View as v;

// TODO refactor: layout context dependency
$checkoutLink = v::get(App::getInstance()->layoutContext(), 'catalog.checkoutLink');
?>
<div class="modal" id="product-added-to-cart">
    <div class="block">
        <span class="close">×</span>
        <p>
            Товар добавлен в корзину
        </p>
        <p>
            <span class="close">Продолжить покупки</span>
            <a href="<?= $checkoutLink ?>">Перейти к оформлению заказа</a>
        </p>
    </div>
</div>
