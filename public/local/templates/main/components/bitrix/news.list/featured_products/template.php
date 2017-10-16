<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\Product;
use App\View as v;
?>
<section class="wrap-items-slider wrap-slider section">
    <div class="section-title">
        <div class="wrap">
            <div class="section-title-block">
                <h2>Актуальные товары</h2>
                <div class="section-title-link">|<a href="<?= v::path('catalog') ?>">все <span class="hidden">товары</span></a></div>
            </div>
            <div class="dots"></div>
        </div>
    </div>
    <div class="wrap">
        <span class="arrows prev"><svg xmlns="http://www.w3.org/2000/svg" width="11" height="18" viewBox="0 0 11 18">
  <defs>
    <style>
      .cls-1 {
          fill: #214385;
          fill-rule: evenodd;
      }
    </style>
  </defs>
  <path id="arrow-left.svg" class="cls-1" d="M313,660l9-9h2l-9,9h-2Zm0,0,9,9h2l-9-9h-2Z" transform="translate(-313 -651)"/>
</svg>
</span>
        <span class="arrows next">
          <svg xmlns="http://www.w3.org/2000/svg" width="11" height="18" viewBox="0 0 11 18">
  <defs>
    <style>
      .cls-1 {
          fill: #214385;
          fill-rule: evenodd;
      }
    </style>
  </defs>
  <path id="arrow-right.svg" class="cls-1" d="M1607,660l-9,9h-2l9-9h2Zm0,0-9-9h-2l9,9h2Z" transform="translate(-1596 -651)"/>
</svg>

        </span>
        <div class="items-slider slider">
            <? foreach ($arResult['ITEMS'] as $item): ?>
                <? $href = fn_get_chainpath($item['IBLOCK_ID'], $item['IBLOCK_SECTION_ID']).$item['CODE'].'.html' ?>
                <? $thumbnail = Product::thumbnail($item) ?>
                <? $price = Product::basePrice($item['ID']) ?>
                <a href="<?= $href ?>" class="item-box">
                    <div class="img">
                        <img src="<?= $thumbnail['SRC'] ?>" alt="<?= $thumbnail['ALT'] ?>">
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
            <? endforeach ?>
        </div>
    </div>
</section>