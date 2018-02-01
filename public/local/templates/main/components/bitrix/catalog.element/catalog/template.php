<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\Product;
use App\View as v;
?>
<? v::appendToView('modals', v::render('partials/catalog/product_added_to_cart_modal.php')) ?>

<? $this->SetViewTarget('bitrix:catalog.element/catalog/additional_text') ?>
<? $value = $arResult['PROPERTIES']['ADDITIONAL_TEXT']['~VALUE'] ?>
<? if (!v::isEmpty($value)): ?>
    <section class="catalog-about">
        <div class="wrap">
            <div class="editable-area wrap-min">
                <?= $value['TEXT'] ?>
            </div>
        </div>
    </section>
<? endif ?>
<? $this->EndViewTarget() ?>

<form action="<?= POST_FORM_ACTION_URI ?>" method="post" enctype="multipart/form-data">
    <? // TODO template ?>
    <div class="back-link">
        <a href="javascript:history.go(-1)"><?= $arResult['NAME'] ?></a>
    </div>
    <div class="catalog-title hidden">
        <h2><?= $arResult['NAME'] ?></h2>
        <?= v::render('partials/catalog/title_logo.php', [
            'picture' => $arResult['BRAND']['PICTURE']
        ]) ?>
    </div>
    <div class="ctalog-inner" id="<?= v::addEditingActions($arResult, $this) ?>">
        <div class="wrap-slider-item">
            <div class="slider-item-main">
                <? $images = Product::galleryImages($arResult) ?>
                <? if (v::isEmpty($images)): ?>
                    <div class="slide">
                        <div class="no-img-placeholder"><div class="content"></div></div>
                    </div>
                <? else: ?>
                    <? foreach ($images as $img): ?>
                        <? $resized = v::resize($img, ...Product::IMAGE_FULL) ?>
                        <div class="slide">
                            <a href="<?= $resized ?>"
                               data-fancybox="product-gallery"
                               class="img"
                               style="background-image: url('<?= $resized ?>')"
                               title="<?= $img['ALT'] ?>">
                            </a>
                        </div>
                    <? endforeach ?>
                <? endif ?>
            </div>
            <div class="slider-item-thumbs">
                <? foreach ($images as $img): ?>
                    <div class="slide">
                        <a class="img"
                           style="background-image: url('<?= v::resize($img, ...Product::IMAGE_SMALL) ?>')"
                           title="<?= $img['ALT'] ?>"></a>
                    </div>
                <? endforeach ?>
            </div>
        </div>
        <div class="catalog-item-info">
            <? if ($arResult['CAN_BUY']): ?>
                <p class="catalog-item-stock hidden">В наличии</p>
            <? else: ?>
                <p class="catalog-item-stock catalog-item-stock--out hidden">Нет в наличии</p>
            <? endif ?>
            <div class="wrap-catalog-hidden-block">
                <div class="open-catalog-hidden-block">Характеристики и описание</div>
                <div class="catalog-hidden-block">
                    <? if (!v::isEmpty($arResult['PROPERTIES']['ARTNUMBER']['VALUE'])): ?>
                        <div class="field">
                            <span class="label">Артикул:</span>
                            <span class="value"><?= $arResult['PROPERTIES']['ARTNUMBER']['VALUE'] ?></span>
                        </div>
                    <? endif ?>
                    <? if (!v::isEmpty($arResult['PROPERTIES']['IN_MODEL']['VALUE'])): ?>
                        <div class="field">
                            <span class="label">К моделям:</span>
                            <span class="value"><?= $arResult['PROPERTIES']['IN_MODEL']['VALUE'] ?></span>
                        </div>
                    <? endif ?>
                    <? if (!v::isEmpty($arResult['PROPERTIES']['IN_TYPE']['VALUE'])): ?>
                        <div class="field">
                            <span class="label">К типу:</span>
                            <span class="value"><?= $arResult['PROPERTIES']['IN_TYPE']['VALUE'] ?></span>
                        </div>
                    <? endif ?>
                    <? $discount = $arResult['PROPERTIES']['DISCOUNT']['VALUE'] ?>
                    <? if ($arResult['PROPERTIES']['PROP']['VALUE'] == Product::HAS_DISCOUNT && !v::isEmpty($discount)): ?>
                        <div class="field">
                            <span class="label">Скидка:</span>
                            <span class="value"><?= $discount ?></span>
                        </div>
                    <? endif ?>
                    <? if (!v::isEmpty($arResult['DETAIL_TEXT'])): ?>
                        <div class="field">
                            <span class="label">Описание:</span>
                            <div class="editable-area value">
                                <?= $arResult['DETAIL_TEXT'] ?>
                            </div>
                        </div>
                    <? endif ?>
                    <? $otherUrl = $arResult['PROPERTIES']['OTHER_SITE_URL']['VALUE'] ?>
                    <? if (!v::isEmpty($otherUrl)): ?>
                        <div class="field">
                            <span class="label">Ссылка на сайт:</span>
                            <div class="value">
                                <a href="<?= $otherUrl ?>" target="_blank"><?= $otherUrl ?></a>
                            </div>
                        </div>
                    <? endif ?>
                </div>
            </div>
            <?// <p class="check-out">Перед покупкой проверьте тип вашего изделия!</p> ?>
            <div class="catalog-item-doc">
                <? // TODO ?>
                <?// <a class="TODO-mockup table-link" href="#">Таблица соответствия</a> ?>
                <? $howCheckUrl = $arResult['PROPERTIES']['HOW_CHECK_URL']['VALUE'] ?>
                <? if (!v::isEmpty($howCheckUrl)): ?>
                    <a class="type-link" href="<?= $howCheckUrl ?>" target="_blank">Проверить тип изделия</a>
                <? endif ?>
            </div>
            <? $oldPrice = $arResult['PROPERTIES']['OLD_PRICE']['VALUE'] ?>
            <? if (!v::isEmpty($oldPrice)): ?>
                <div class="catalog-item-price catalog-item-price--old-price">
                    <div class="price">
                        <? $formatted = CCurrencyLang::CurrencyFormat($oldPrice, Product::CURRENCY, true) ?>
                        <? // TODO (span (span price) currency) ?>
                        <span class="price-text"><span><?= $formatted ?></span></span>
                    </div>
                </div>
            <? endif ?>
            <? $price = Product::basePrice($arResult['ID']) ?>
            <? $formatted = CCurrencyLang::CurrencyFormat($price['PRICE'], $price['CURRENCY'], true) ?>
            <? // TODO hide if not can_buy ?>
            <div class="catalog-item-price">
                <div class="price">
                    <? // TODO (span (span price) currency) ?>
                    <span class="price-text"><span><?= $formatted ?></span></span>
                    <span class="separator hidden">X</span>
                    <input name="quantity" type="number" class="quantity hidden" min="1" value="1">
                </div>
                <? if ($arResult['CAN_BUY']): ?>
                    <p class="catalog-item-stock">В наличии</p>
                <? else: ?>
                    <p class="catalog-item-stock catalog-item-stock--out">Нет в наличии</p>
                <? endif ?>
                <? if ($arResult['CAN_BUY']): ?>
                    <button type="button" class="buy-button catalog-cart-btn" data-id="<?= $arResult['ID'] ?>">
                        <span>В корзину</span>
                    </button>
                <? endif ?>
            </div>
        </div>
    </div>
</form>
