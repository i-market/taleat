<?
use App\Layout;
use App\Components;
?>
<? Layout::showCatalogWrapper('header') ?>
<div class="catalog-pages-block">
    <div class="catalog-pages-block-white">
        <? // TODO breadcrumbs ?>
        <ul class="bread-crumbs">
            <li class="bread-crumbs-item"><a href="#" class="link">Каталог</a></li>
            <li class="bread-crumbs-item"><span class="link">Braun</span></li>
        </ul>
        <?$APPLICATION->IncludeComponent(
            "bitrix:catalog.element",
            "catalog",
            array(
                "IBLOCK_TYPE" => "catalog",
                "IBLOCK_ID" => $iblock_id,
                "ELEMENT_ID" => $arItem["ID"],
                "ELEMENT_CODE" => "",
                "SECTION_ID" => "",
                "SECTION_CODE" => "",
                "PROPERTY_CODE" => array(
                    0 => "",
                    1 => "HOW_CHECK_URL",
                    2 => "OTHER_SITE_URL",
                    3 => "ARTNUMBER",
                    4 => "IN_MODEL",
                    5 => "IN_TYPE",
                    6 => "PROP",
                    7 => "DISCOUNT",
                    8 => "OLD_PRICE",
                    9 => "",
                ),
                "OFFERS_LIMIT" => "0",
                "SECTION_URL" => "",
                "DETAIL_URL" => "",
                "BASKET_URL" => "/personal/basket.php",
                "ACTION_VARIABLE" => "action",
                "PRODUCT_ID_VARIABLE" => "id",
                "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                "PRODUCT_PROPS_VARIABLE" => "prop",
                "SECTION_ID_VARIABLE" => "SECTION_ID",
                "CACHE_TYPE" => "N",
                "CACHE_TIME" => "36000000",
                "CACHE_GROUPS" => "Y",
                "META_KEYWORDS" => "-",
                "META_DESCRIPTION" => "-",
                "BROWSER_TITLE" => "-",
                "SET_TITLE" => "Y",
                "SET_STATUS_404" => "N",
                "ADD_SECTIONS_CHAIN" => "N",
                "USE_ELEMENT_COUNTER" => "Y",
                "PRICE_CODE" => array(
                    0 => "BASE",
                ),
                "USE_PRICE_COUNT" => "Y",
                "SHOW_PRICE_COUNT" => "1",
                "PRICE_VAT_INCLUDE" => "Y",
                "PRICE_VAT_SHOW_VALUE" => "Y",
                "PRODUCT_PROPERTIES" => array(
                ),
                "USE_PRODUCT_QUANTITY" => "Y",
                "CONVERT_CURRENCY" => "Y",
                "CURRENCY_ID" => "RUB",
                "LINK_IBLOCK_TYPE" => "",
                "LINK_IBLOCK_ID" => "",
                "LINK_PROPERTY_SID" => "",
                "LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
                "COMPONENT_TEMPLATE" => "catalog",
                "SHOW_DEACTIVATED" => "N",
                "HIDE_NOT_AVAILABLE_OFFERS" => "N",
                "BACKGROUND_IMAGE" => "-",
                "CHECK_SECTION_ID_VARIABLE" => "N",
                "SEF_MODE" => "N",
                "SET_CANONICAL_URL" => "N",
                "SET_BROWSER_TITLE" => "Y",
                "SET_META_KEYWORDS" => "Y",
                "SET_META_DESCRIPTION" => "Y",
                "SET_LAST_MODIFIED" => "N",
                "USE_MAIN_ELEMENT_SECTION" => "N",
                "STRICT_SECTION_CHECK" => "N",
                "ADD_ELEMENT_CHAIN" => "N",
                "DISPLAY_COMPARE" => "N",
                "ADD_PROPERTIES_TO_BASKET" => "Y",
                "PARTIAL_PRODUCT_PROPERTIES" => "N",
                "USE_GIFTS_DETAIL" => "Y",
                "USE_GIFTS_MAIN_PR_SECTION_LIST" => "Y",
                "GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => "3",
                "GIFTS_DETAIL_HIDE_BLOCK_TITLE" => "N",
                "GIFTS_DETAIL_BLOCK_TITLE" => "Выберите один из подарков",
                "GIFTS_DETAIL_TEXT_LABEL_GIFT" => "Подарок",
                "GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
                "GIFTS_SHOW_OLD_PRICE" => "Y",
                "GIFTS_SHOW_NAME" => "Y",
                "GIFTS_SHOW_IMAGE" => "Y",
                "GIFTS_MESS_BTN_BUY" => "Выбрать",
                "GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT" => "3",
                "GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE" => "N",
                "GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE" => "Выберите один из товаров, чтобы получить подарок",
                "SHOW_404" => "N",
                "MESSAGE_404" => "",
                "COMPATIBLE_MODE" => "Y",
                "DISABLE_INIT_JS_IN_COMPONENT" => "N",
                "SET_VIEWED_IN_COMPONENT" => "N"
            ),
            false
        );?>
    </div>
    <div class="banner">
        <a href="#" class="first">
            <img src="images/pic/banner.jpg" alt="">
        </a>
        <a href="#" class="second">
            <img src="images/pic/banner-2.jpg" alt="">
        </a>
    </div>
</div>
<? Layout::showCatalogWrapper('footer') ?>
<section class="wrap-items-slider wrap-slider section">
    <div class="section-title">
        <div class="wrap">
            <div class="section-title-block">
                <h2>Похожие товары</h2>
                <div class="section-title-link">|<a href="#">все <span class="hidden">товары этой категории</span></a></div>
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
            <a href="#" class="item-box">
                <div class="img">
                    <img src="images/pic/items/1.png" alt="">
                </div>
                <div class="info">
                    <p class="label-unfo">
                        <span class="articul">SX1045</span>
                        <span class="label-name">Braun</span>
                    </p>
                    <p class="name">Колба для кофеварки Delonghi</p>
                    <div class="price-info">
                        <span class="price"><span>1680</span> руб.</span>
                        <span class="cart"></span>
                    </div>
                </div>
            </a>
            <a href="#" class="item-box">
                <div class="img">
                    <img src="images/pic/items/2.png" alt="">
                </div>
                <div class="info">
                    <p class="label-unfo">
                        <span class="articul">SX1045</span>
                        <span class="label-name">Braun</span>
                    </p>
                    <p class="name">Колба для кофеварки Delonghi</p>
                    <div class="price-info">
                        <span class="price"><span>1680</span> руб.</span>
                        <span class="cart"></span>
                    </div>
                </div>
            </a>
            <a href="#" class="item-box">
                <div class="img">
                    <img src="images/pic/items/3.png" alt="">
                </div>
                <div class="info">
                    <p class="label-unfo">
                        <span class="articul">SX1045</span>
                        <span class="label-name">Braun</span>
                    </p>
                    <p class="name">Колба для кофеварки Delonghi</p>
                    <div class="price-info">
                        <span class="price"><span>1680</span> руб.</span>
                        <span class="cart"></span>
                    </div>
                </div>
            </a>
            <a href="#" class="item-box">
                <div class="img">
                    <img src="images/pic/items/4.png" alt="">
                </div>
                <div class="info">
                    <p class="label-unfo">
                        <span class="articul">SX1045</span>
                        <span class="label-name">Braun</span>
                    </p>
                    <p class="name">Колба для кофеварки Delonghi</p>
                    <div class="price-info">
                        <span class="price"><span>1680</span> руб.</span>
                        <span class="cart"></span>
                    </div>
                </div>
            </a>
            <a href="#" class="item-box">
                <div class="img">
                    <img src="images/pic/items/3.png" alt="">
                </div>
                <div class="info">
                    <p class="label-unfo">
                        <span class="articul">SX1045</span>
                        <span class="label-name">Braun</span>
                    </p>
                    <p class="name">Колба для кофеварки Delonghi</p>
                    <div class="price-info">
                        <span class="price"><span>1680</span> руб.</span>
                        <span class="cart"></span>
                    </div>
                </div>
            </a>
        </div>
    </div>
</section>
<? // TODO viewed (bitrix:catalog.products.viewed) ?>
<section class="wrap-items-slider wrap-slider section">
    <div class="section-title">
        <div class="wrap">
            <div class="section-title-block">
                <h2>Вы недавно смотрели</h2>
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
            <a href="#" class="item-box">
                <div class="img">
                    <img src="images/pic/items/3.png" alt="">
                </div>
                <div class="info">
                    <p class="label-unfo">
                        <span class="articul">SX1045</span>
                        <span class="label-name">Braun</span>
                    </p>
                    <p class="name">Колба для кофеварки Delonghi</p>
                    <div class="price-info">
                        <span class="price"><span>1680</span> руб.</span>
                        <span class="cart"></span>
                    </div>
                </div>
            </a>
            <a href="#" class="item-box">
                <div class="img">
                    <img src="images/pic/items/4.png" alt="">
                </div>
                <div class="info">
                    <p class="label-unfo">
                        <span class="articul">SX1045</span>
                        <span class="label-name">Braun</span>
                    </p>
                    <p class="name">Колба для кофеварки Delonghi</p>
                    <div class="price-info">
                        <span class="price"><span>1680</span> руб.</span>
                        <span class="cart"></span>
                    </div>
                </div>
            </a>
            <a href="#" class="item-box">
                <div class="img">
                    <img src="images/pic/items/3.png" alt="">
                </div>
                <div class="info">
                    <p class="label-unfo">
                        <span class="articul">SX1045</span>
                        <span class="label-name">Braun</span>
                    </p>
                    <p class="name">Колба для кофеварки Delonghi</p>
                    <div class="price-info">
                        <span class="price"><span>1680</span> руб.</span>
                        <span class="cart"></span>
                    </div>
                </div>
            </a>
            <a href="#" class="item-box">
                <div class="img">
                    <img src="images/pic/items/1.png" alt="">
                </div>
                <div class="info">
                    <p class="label-unfo">
                        <span class="articul">SX1045</span>
                        <span class="label-name">Braun</span>
                    </p>
                    <p class="name">Колба для кофеварки Delonghi</p>
                    <div class="price-info">
                        <span class="price"><span>1680</span> руб.</span>
                        <span class="cart"></span>
                    </div>
                </div>
            </a>
            <a href="#" class="item-box">
                <div class="img">
                    <img src="images/pic/items/2.png" alt="">
                </div>
                <div class="info">
                    <p class="label-unfo">
                        <span class="articul">SX1045</span>
                        <span class="label-name">Braun</span>
                    </p>
                    <p class="name">Колба для кофеварки Delonghi</p>
                    <div class="price-info">
                        <span class="price"><span>1680</span> руб.</span>
                        <span class="cart"></span>
                    </div>
                </div>
            </a>
        </div>
    </div>
</section>
<? Components::showVideosSection() ?>
<section class="about">
    <div class="section-title">
        <div class="wrap">
            <div class="section-title-block">
                <h2>Запчасти для кухонных комбайнов Braun</h2>
            </div>
        </div>
    </div>
    <div class="wrap">
        <div class="wrap-min">
            <p class="paragraph">Не следует, однако забывать, что рамки и место обучения кадров обеспечивает широкому кругу (специалистов) участие в формировании направлений прогрессивного развития. Товарищи! консультация с широким активом представляет собой интересный эксперимент проверки направлений прогрессивного развития. Товарищи! реализация намеченных плановых заданий влечет за собой процесс внедрения и модернизации модели развития. С другой стороны укрепление и развитие структуры позволяет выполнять важные задания по разработке позиций, занимаемых участниками в отношении поставленных задач. Идейные соображения высшего порядка, а также консультация с широким активом позволяет выполнять важные задания по разработке форм развития. Разнообразный и богатый опыт укрепление и развитие структуры играет важную роль в формировании дальнейших направлений развития.</p>
            <a href="#" class="read-more"><span>читать дальше</span></a>
        </div>
    </div>
</section

