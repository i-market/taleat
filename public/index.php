<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Сетки к бритвам, зубные насадки к электрическим зубным щеткам, запасные части к Браун, Braun, аксессуары к бытовой технике");
$APPLICATION->SetPageProperty("description", "Мы предлагаем широкий ассортимент аксессуаров  к бытовой технике  фирмы Braun (Браун)");
$APPLICATION->SetTitle("Главная | TALEAT");

use App\Iblock;
use App\View as v;
use Bex\Tools\Iblock\IblockTools;

?>

<?
global $arFiltNew;

$arFiltNew=array("PROPERTY_IS_FEATURED_VALUE" => Iblock::CHECKBOX_TRUE_VALUE);
?>

<? $APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "brands_slider",
    Array(
        "ACTIVE_DATE_FORMAT" => "j F Y",
        "ADD_SECTIONS_CHAIN" => "N",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "FIELD_CODE" => array("", ""),
        "FILTER_NAME" => "",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "IBLOCK_ID" => IblockTools::find(Iblock::CONTENT_TYPE, Iblock::BRANDS)->id(),
        "IBLOCK_TYPE" => Iblock::CONTENT_TYPE,
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "INCLUDE_SUBSECTIONS" => "Y",
        "MESSAGE_404" => "",
        "NEWS_COUNT" => PHP_INT_MAX,
        "PAGER_BASE_LINK_ENABLE" => "N",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => ".default",
        "PAGER_TITLE" => '',
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "PREVIEW_TRUNCATE_LEN" => "",
        "PROPERTY_CODE" => array("", ""),
        "SET_BROWSER_TITLE" => "N",
        "SET_LAST_MODIFIED" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "N",
        "SHOW_404" => "N",
        "SORT_BY1" => "ACTIVE_FROM",
        "SORT_BY2" => "SORT",
        "SORT_ORDER1" => "DESC",
        "SORT_ORDER2" => "ASC"
    )
); ?>
<?$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "featured_products",
    array(
        "IBLOCK_TYPE" => "catalog",
        "IBLOCK_ID" => "3",
        "NEWS_COUNT" => "",
        "SORT_BY1" => "SORT",
        "SORT_ORDER1" => "DESC",
        "SORT_BY2" => "SORT",
        "SORT_ORDER2" => "ASC",
        "FILTER_NAME" => "arFiltNew",
        "FIELD_CODE" => array(
            0 => "DETAIL_PICTURE",
            1 => "",
        ),
        "PROPERTY_CODE" => array(
            0 => "ARTNUMBER",
            1 => "MANUFACTURER",
            2 => "MATERIAL",
            3 => "COLOR",
            4 => "SPECIALOFFER",
            5 => "NEWPRODUCT",
            6 => "WIDTH",
            7 => "LENGHT",
            8 => "SIZE",
            9 => "PROP",
            10 => "STORAGE_COMPARTMENT",
            11 => "HEIGHT",
            12 => "DEPTH",
            13 => "TITLE",
            14 => "IN_MODEL",
            15 => "IN_TYPE",
            16 => "HOW_CHECK_URL",
            17 => "KEYWORDS",
            18 => "LIGHTS",
            19 => "SHELVES",
            20 => "META_DESCRIPTION",
            21 => "POP_TOVAR",
            22 => "OTHER_SITE_URL",
            23 => "OLD_PRICE",
            24 => "TYPE",
            25 => "DISCOUNT",
            26 => "CORNER",
            27 => "SEATS",
            28 => "MINIMUM_PRICE",
            29 => "MAXIMUM_PRICE",
            30 => "LINK",
            31 => "space",
            32 => "",
        ),
        "CHECK_DATES" => "N",
        "DETAIL_URL" => "",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "N",
        "AJAX_OPTION_HISTORY" => "N",
        "CACHE_TYPE" => "N",
        "CACHE_TIME" => "3600",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "PREVIEW_TRUNCATE_LEN" => "",
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "SET_TITLE" => "N",
        "SET_STATUS_404" => "N",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "ADD_SECTIONS_CHAIN" => "N",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "PAGER_TITLE" => "",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => "",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "AJAX_OPTION_ADDITIONAL" => "",
        "COMPONENT_TEMPLATE" => "featured_products",
        "SET_BROWSER_TITLE" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_LAST_MODIFIED" => "N",
        "INCLUDE_SUBSECTIONS" => "Y",
        "PAGER_BASE_LINK_ENABLE" => "N",
        "SHOW_404" => "N",
        "MESSAGE_404" => ""
    ),
    false
);?>
<? $APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "clients_slider",
    Array(
        "ACTIVE_DATE_FORMAT" => "j F Y",
        "ADD_SECTIONS_CHAIN" => "N",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "FIELD_CODE" => array("", ""),
        "FILTER_NAME" => "",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "IBLOCK_ID" => IblockTools::find(Iblock::CONTENT_TYPE, Iblock::CLIENTS)->id(),
        "IBLOCK_TYPE" => Iblock::CONTENT_TYPE,
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "INCLUDE_SUBSECTIONS" => "Y",
        "MESSAGE_404" => "",
        "NEWS_COUNT" => PHP_INT_MAX, // TODO check news count param
        "PAGER_BASE_LINK_ENABLE" => "N",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => ".default",
        "PAGER_TITLE" => '',
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "PREVIEW_TRUNCATE_LEN" => "",
        "PROPERTY_CODE" => array("", ""),
        "SET_BROWSER_TITLE" => "N",
        "SET_LAST_MODIFIED" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "N",
        "SHOW_404" => "N",
        "SORT_BY1" => "ACTIVE_FROM",
        "SORT_BY2" => "SORT",
        "SORT_ORDER1" => "DESC",
        "SORT_ORDER2" => "ASC"
    )
); ?>
<? $APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "certificates_slider",
    Array(
        "ACTIVE_DATE_FORMAT" => "j F Y",
        "ADD_SECTIONS_CHAIN" => "N",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "FIELD_CODE" => array("", ""),
        "FILTER_NAME" => "",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "IBLOCK_ID" => IblockTools::find(Iblock::CONTENT_TYPE, Iblock::CERTIFICATES)->id(),
        "IBLOCK_TYPE" => Iblock::CONTENT_TYPE,
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "INCLUDE_SUBSECTIONS" => "Y",
        "MESSAGE_404" => "",
        "NEWS_COUNT" => PHP_INT_MAX,
        "PAGER_BASE_LINK_ENABLE" => "N",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => ".default",
        "PAGER_TITLE" => '',
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "PREVIEW_TRUNCATE_LEN" => "",
        "PROPERTY_CODE" => array("FILE"),
        "SET_BROWSER_TITLE" => "N",
        "SET_LAST_MODIFIED" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "N",
        "SHOW_404" => "N",
        "SORT_BY1" => "ACTIVE_FROM",
        "SORT_BY2" => "SORT",
        "SORT_ORDER1" => "DESC",
        "SORT_ORDER2" => "ASC"
    )
); ?>
<? $APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "latest_news_section",
    Array(
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "ADD_SECTIONS_CHAIN" => "N",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "FIELD_CODE" => array("", ""),
        "FILTER_NAME" => "",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "IBLOCK_ID" => IblockTools::find("news", "news")->id(),
        "IBLOCK_TYPE" => "news",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "INCLUDE_SUBSECTIONS" => "Y",
        "MESSAGE_404" => "",
        "NEWS_COUNT" => 2,
        "PAGER_BASE_LINK_ENABLE" => "N",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => ".default",
        "PAGER_TITLE" => '',
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "PREVIEW_TRUNCATE_LEN" => "",
        "PROPERTY_CODE" => array("", ""),
        "SET_BROWSER_TITLE" => "N",
        "SET_LAST_MODIFIED" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "N",
        "SHOW_404" => "N",
        "SORT_BY1" => "ACTIVE_FROM",
        "SORT_BY2" => "SORT",
        "SORT_ORDER1" => "DESC",
        "SORT_ORDER2" => "ASC"
    )
); ?>
<section class="useful section hidden">
    <div class="section-title">
        <div class="wrap">
            <div class="section-title-block">
                <h2>Поленое</h2>
                <div class="section-title-link">|<a href="#">все статьи</a></div>
            </div>
        </div>
    </div>
    <div class="wrap useful-block">
        <div class="left">
            <div class="wrap-useful-slider">
                <div class="useful-slider">
                    <div class="slide">
                        <div class="img">
                            <img src="<?= v::asset('images/pic/pic-4.jpg') ?>" alt="">
                        </div>
                        <div class="info">
                            <p class="title">Безопасность при ремонте бытовых приборов и техники</p>
                            <p class="paragraph">Стиральные машины, пылесосы, обогреватели, кондиционеры, холодильники – далеко не полный перечень устройств, без которых жизнь современного горожанина превратилась бы в настоящий ад. Люди просто отвыкли обходиться без всего этого. Но бытовая техника периодически выходит из строя, и встает вопрос, что с ней делать.  Всем ли доступен ремонт бытовой техники? Давайте об этом поговорим.</p>
                            <p class="more"><a href="#">Подробнее...</a></p>
                        </div>
                    </div>
                    <div class="slide">
                        <div class="img">
                            <img src="<?= v::asset('images/pic/pic-4.jpg') ?>" alt="">
                        </div>
                        <div class="info">
                            <p class="title">Безопасность при ремонте бытовых приборов и техники</p>
                            <p class="paragraph">Стиральные машины, пылесосы, обогреватели, кондиционеры, холодильники – далеко не полный перечень устройств, без которых жизнь современного горожанина превратилась бы в настоящий ад. Люди просто отвыкли обходиться без всего этого. Но бытовая техника периодически выходит из строя, и встает вопрос, что с ней делать.  Всем ли доступен ремонт бытовой техники? Давайте об этом поговорим.</p>
                            <p class="more"><a href="#">Подробнее...</a></p>
                        </div>
                    </div>
                    <div class="slide">
                        <div class="img">
                            <img src="<?= v::asset('images/pic/pic-4.jpg') ?>" alt="">
                        </div>
                        <div class="info">
                            <p class="title">Безопасность при ремонте бытовых приборов и техники</p>
                            <p class="paragraph">Стиральные машины, пылесосы, обогреватели, кондиционеры, холодильники – далеко не полный перечень устройств, без которых жизнь современного горожанина превратилась бы в настоящий ад. Люди просто отвыкли обходиться без всего этого. Но бытовая техника периодически выходит из строя, и встает вопрос, что с ней делать.  Всем ли доступен ремонт бытовой техники? Давайте об этом поговорим.</p>
                            <p class="more"><a href="#">Подробнее...</a></p>
                        </div>
                    </div>
                </div>
                <div class="dots"></div>
            </div>
        </div>
        <div class="right">
            <div class="useful-item">
                <p class="title">Бренды которые мы ремонтируем</p>
                <p class="paragraph">Стиральные машины, пылесосы, обогреватели, кондиционеры, холодильники – далеко не полный...</p>
                <p class="more"><a href="#">Подробнее...</a></p>
            </div>
            <div class="useful-item">
                <p class="title">Бренды которые мы ремонтируем</p>
                <p class="paragraph">Стиральные машины, пылесосы, обогреватели, кондиционеры, холодильники – далеко не полный...</p>
                <p class="more"><a href="#">Подробнее...</a></p>
            </div>
            <div class="useful-item">
                <p class="title">Бренды которые мы ремонтируем</p>
                <p class="paragraph">Стиральные машины, пылесосы, обогреватели, кондиционеры, холодильники – далеко не полный...</p>
                <p class="more"><a href="#">Подробнее...</a></p>
            </div>
            <div class="useful-item">
                <p class="title">Бренды которые мы ремонтируем</p>
                <p class="paragraph">Стиральные машины, пылесосы, обогреватели, кондиционеры, холодильники – далеко не полный...</p>
                <p class="more"><a href="#">Подробнее...</a></p>
            </div>
        </div>
    </div>
</section>
<section class="videos section">
    <div class="section-title">
        <div class="wrap">
            <div class="section-title-block">
                <h2>Полезное видео</h2>
                <div class="section-title-link">|<a href="#">все <span class="hidden">видеуроки</span></a></div>
            </div>
        </div>
    </div>
    <div class="wrap">
        <div class="wrap-min">
            <div class="grid">
                <div class="col col-3 video-item">
                    <iframe src="https://www.youtube.com/embed/hj7ZYjVFDGI?rel=0" frameborder="0" allowfullscreen></iframe>
                    <p class="name">Ремонт бытовой техники на дому в Москве</p>
                </div>
                <div class="col col-3 video-item">
                    <iframe src="https://www.youtube.com/embed/hj7ZYjVFDGI?rel=0" frameborder="0" allowfullscreen></iframe>
                    <p class="name">Далеко не полный перечень устройств, без которых жизнь современного</p>
                </div>
                <div class="col col-3 video-item">
                    <iframe src="https://www.youtube.com/embed/hj7ZYjVFDGI?rel=0" frameborder="0" allowfullscreen></iframe>
                    <p class="name">Ремонт бытовой техники на дому в Москве</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="about">
    <div class="section-title">
        <div class="wrap">
            <div class="section-title-block">
               <div class="editable-area">
                   <? $APPLICATION->IncludeComponent(
                       "bitrix:main.include",
                       "",
                       Array(
                           "AREA_FILE_SHOW" => "file",
                           "PATH" => v::includedArea('homepage/about_heading.php')
                       )
                   ); ?>
               </div>
            </div>
        </div>
    </div>
    <div class="wrap">
        <div class="wrap-min">
            <div class="editable-area">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => v::includedArea('homepage/about_body.php')
                    )
                ); ?>
            </div>
            <a href="#" class="read-more"><span>читать дальше</span></a>
        </div>
    </div>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
