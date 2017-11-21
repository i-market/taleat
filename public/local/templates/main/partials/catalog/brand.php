<?
/**
 * @global $iblock_id
 * @global $arSection
 */

use App\Product;
use App\Components;
use App\View as v;
use App\Layout;
use App\Iblock;
use Bex\Tools\Iblock\IblockTools;
?>
<? Layout::showCatalogWrapper('header') ?>
<div class="catalog-pages-block">
    <? Layout::showBreadcrumbs() ?>
    <?$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "brand", Array(
        "IBLOCK_TYPE" => "catalog",	// Тип инфоблока
        "IBLOCK_ID" => $iblock_id,	// Инфоблок
        "SECTION_ID" => $arSection["ID"],	// ID раздела
        "SECTION_CODE" => "",	// Код раздела
        "COUNT_ELEMENTS" => "N",	// Показывать количество элементов в разделе
        "TOP_DEPTH" => "1",	// Максимальная отображаемая глубина разделов
        "SECTION_FIELDS" => array(	// Поля разделов
            0 => "PICTURE",
            1 => "",
        ),
        "SECTION_USER_FIELDS" => array(	// Свойства разделов
            0 => "",
            1 => "",
        ),
        "SECTION_URL" => "",	// URL, ведущий на страницу с содержимым раздела
        "CACHE_TYPE" => "N",	// Тип кеширования
        "CACHE_TIME" => "36000000",	// Время кеширования (сек.)
        "CACHE_GROUPS" => "Y",	// Учитывать права доступа
        "ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
    ),
        false
    );?>
</div>
<? Layout::showCatalogWrapper('footer') ?>
<?= v::render('partials/catalog/recommended.php', ['items' => Product::recommended($arSection)]) ?>
<section class="banner">
    <div class="wrap">
        <? Components::showBanner('catalog-terms') ?>
    </div>
</section>
<? Components::showArticleVideoSection() ?>
