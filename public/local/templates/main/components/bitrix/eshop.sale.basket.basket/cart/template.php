<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
use App\Product;
use Core\Util;

/*
 * item types:
 * AnDelCanBuy   - available
 * DelDelCanBuy  - delay
 * nAnCanBuy     - not available
 * ProdSubscribe - subscribe
 */
?>
<form method="post" action="<?= POST_FORM_ACTION_URI ?>" name="basket_form">
    <section class="lk">
        <div class="section-title">
            <div class="wrap">
                <div class="section-title-block">
                    <h2><?= $APPLICATION->GetTitle(false) ?></h2>
                </div>
                <? // TODO link ?>
                <a class="TODO-mockup simple-btn" href="#">Продолжить покупки</a>
            </div>
        </div>
        <div class="wrap">
            <div class="wrap-cart-table finger-block">
                <span class="finger"></span>
                <div class="cart-table">
                    <div class="thead">
                        <div class="tr">
                            <div class="td"></div>
                            <div class="td"></div>
                            <div class="td">Товар</div>
                            <div class="td">Количество</div>
                            <div class="td">Цена</div>
                            <div class="td">Итого</div>
                            <div class="td red">Удалить все</div>
                        </div>
                    </div>
                    <div class="tbody">
                        <? // available ?>
                        <? foreach ($arResult['ITEMS']['AnDelCanBuy'] as $idx => $item): ?>
                            <? $thumbnail = Product::thumbnail($item) ?>
                            <div class="tr">
                                <div class="td"><?= $idx + 1 ?></div>
                                <div class="td">
                                    <div class="img">
                                        <? if (!v::isEmpty($thumbnail)): ?>
                                            <img src="<?= $thumbnail['SRC'] ?>" alt="<?= $thumbnail['ALT'] ?>">
                                        <? else: ?>
                                            <div class="no-img-placeholder"><div class="content"></div></div>
                                        <? endif ?>
                                    </div>
                                </div>
                                <div class="td">
                                    <div class="good-info">
                                        <? // TODO properties ?>
                                        <span class="TODO-mockup articul">SX1045</span>
                                        <span class="label"><?= Product::brand($item) ?></span>
                                    </div>
                                    <a class="name" href="<?= Product::elementUrl($item) ?>"><?= $item['NAME'] ?></a>
                                </div>
                                <div class="td">
                                    <? // TODO ?>
                                    <div class="TODO-mockup input-number" data-min="1" data-max="999">
                                        <span class="input-number-decrement" data-decrement></span>
                                        <input type="text" value="1">
                                        <span class="input-number-increment" data-increment></span>
                                    </div>
                                </div>
                                <div class="td">
                                    <? // TODO (span (span price) currency) ?>
                                    <span class="price"><?= $item['FULL_PRICE_FORMATED'] ?></span>
                                </div>
                                <div class="td">
                                    <? $formatted = CCurrencyLang::CurrencyFormat($item['PRICE'] * $item['QUANTITY'], $item['CURRENCY'], true) ?>
                                    <? // TODO (span (span price) currency) ?>
                                    <span class="price-total"><?= $formatted ?></span>
                                </div>
                                <div class="td">
                                    <? // TODO delete button ?>
                                    <span class="TODO-mockup delete"></span>
                                </div>
                            </div>
                        <? endforeach ?>
                    </div>
                </div>
            </div>
            <div class="cart-table-total">
                <div class="top">
                    <? $quantity = count($arResult['ITEMS']['AnDelCanBuy']) ?>
                    <? // TODO (span (span price) currency) ?>
                    <p>Итого: <?= $quantity.' '.Util::units($quantity, 'товар', 'товара', 'товаров') ?>, <span class="new-line">на сумму<span class="price"><?= $arResult['allSum_FORMATED'] ?></span></span></p>
                </div>
                <div class="bottom">
                    <div class="editable-area text">
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            Array(
                                "AREA_FILE_SHOW" => "file",
                                "PATH" => v::includedArea('personal/cart/bottom.php')
                            )
                        ); ?>
                    </div>
                    <input type="submit" name="BasketOrder" class="yellow-btn" value="Оформить заказ">
                </div>
            </div>
        </div>
    </section>
</form>
