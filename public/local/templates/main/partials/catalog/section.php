<?
/**
 * @global $iblock_id
 * @global $arSection
 * @global $per_page
 */

use App\Components;
use App\Layout;
use App\Catalog;

$state = Catalog::sectionState();
?>
<? Layout::showCatalogWrapper('header') ?>
<div class="catalog-pages-block">
    <? Layout::showBreadcrumbs() ?>
    <? $paginatorView = 'bitrix:catalog.section/products/paginator' ?>
    <? $APPLICATION->IncludeComponent(
        "bitrix:catalog.section",
        "products",
        array(
            "IBLOCK_TYPE" => "catalog",
            "IBLOCK_ID" => $iblock_id,
            "SECTION_ID" => $arSection["ID"],
            "SECTION_CODE" => "",
            "SECTION_USER_FIELDS" => array(
                0 => "",
                1 => "",
            ),
            "ELEMENT_SORT_FIELD" => $state['sort']['field'],
            "ELEMENT_SORT_ORDER" => $state['sort']['order'],
            "FILTER_NAME" => "arFilt",
            "FIELD_CODE" => array(
                0 => "DETAIL_PICTURE",
                1 => "",
            ),
            "INCLUDE_SUBSECTIONS" => "N",
            "SHOW_ALL_WO_SECTION" => "Y",
            "PAGE_ELEMENT_COUNT" => $per_page,
            "LINE_ELEMENT_COUNT" => "2",
            "PROPERTY_CODE" => array(
                0 => "",
                1 => "artikul",
                2 => "",
            ),
            "OFFERS_LIMIT" => "",
            "SECTION_URL" => "",
            "DETAIL_URL" => "",
            "BASKET_URL" => "/personal/basket.php",
            "ACTION_VARIABLE" => "action",
            "PRODUCT_ID_VARIABLE" => "id",
            "PRODUCT_QUANTITY_VARIABLE" => "quantity",
            "PRODUCT_PROPS_VARIABLE" => "prop",
            "SECTION_ID_VARIABLE" => "SECTION_ID",
            "AJAX_MODE" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "N",
            "CACHE_TYPE" => "N",
            "CACHE_TIME" => "36000000",
            "CACHE_GROUPS" => "N",
            "META_KEYWORDS" => "-",
            "META_DESCRIPTION" => "-",
            "BROWSER_TITLE" => "-",
            "ADD_SECTIONS_CHAIN" => "N",
            "DISPLAY_COMPARE" => "N",
            "SET_TITLE" => "N",
            "SET_STATUS_404" => "N",
            "CACHE_FILTER" => "Y",
            "PRICE_CODE" => array(
                0 => "BASE",
            ),
            "USE_PRICE_COUNT" => "N",
            "SHOW_PRICE_COUNT" => "1",
            "PRICE_VAT_INCLUDE" => "N",
            "PRODUCT_PROPERTIES" => array(
            ),
            "USE_PRODUCT_QUANTITY" => "Y",
            "CONVERT_CURRENCY" => "Y",
            "CURRENCY_ID" => "RUB",
            "DISPLAY_TOP_PAGER" => "Y",
            "DISPLAY_BOTTOM_PAGER" => "Y",
            "PAGER_TITLE" => "Товары",
            "PAGER_SHOW_ALWAYS" => "N",
            "PAGER_TEMPLATE" => "",
            "PAGER_DESC_NUMBERING" => "N",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
            "PAGER_SHOW_ALL" => "N",
            "AJAX_OPTION_ADDITIONAL" => "",
            "COMPONENT_TEMPLATE" => "cat",
            "ELEMENT_SORT_FIELD2" => "id",
            "ELEMENT_SORT_ORDER2" => "desc",
            "HIDE_NOT_AVAILABLE" => "N",
            "BACKGROUND_IMAGE" => "-",
            "SEF_MODE" => "N",
            "SET_BROWSER_TITLE" => "Y",
            "SET_META_KEYWORDS" => "Y",
            "SET_META_DESCRIPTION" => "Y",
            "SET_LAST_MODIFIED" => "N",
            "USE_MAIN_ELEMENT_SECTION" => "N",
            "ADD_PROPERTIES_TO_BASKET" => "Y",
            "PARTIAL_PRODUCT_PROPERTIES" => "N",
            "PAGER_BASE_LINK_ENABLE" => "N",
            "SHOW_404" => "N",
            "MESSAGE_404" => "",
            "PAGINATOR_VIEW" => $paginatorView,
            "STATE" => $state
        ),
        false
    ); ?>
</div>
<? Layout::showCatalogWrapper('footer', function () use (&$APPLICATION, $paginatorView) { ?>
    <? $APPLICATION->ShowViewContent($paginatorView) ?>
<? }) ?>
<section class="banner">
    <div class="wrap">
        <? Components::showBanner('catalog-terms') ?>
    </div>
</section>

