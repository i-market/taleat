<?
use App\View as v;
use App\Layout;
use App\Iblock;
use Bex\Tools\Iblock\IblockTools;

/**
 * @global $iblock_id
 * @global $arSection
 */
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
            0 => "",
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
<? // TODO recommended products ?>
<section class="wrap-items-slider wrap-slider section">
    <div class="section-title">
        <div class="wrap">
            <div class="section-title-block">
                <h2>Рекомендуемые товары</h2>
                <div class="section-title-link">|<a href="#">все <span class="hidden">товары</span></a></div>
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
<?$APPLICATION->IncludeComponent(
	"bitrix:news.detail",
	"responsive_banner_section",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_ELEMENT_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"BROWSER_TITLE" => "-",
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
		"ELEMENT_CODE" => "catalog-terms",
		"ELEMENT_ID" => "",
		"FIELD_CODE" => array("PREVIEW_PICTURE"),
		"IBLOCK_ID" => IblockTools::find(Iblock::CONTENT_TYPE, Iblock::RESPONSIVE_BANNERS)->id(),
		"IBLOCK_TYPE" => Iblock::CONTENT_TYPE,
		"IBLOCK_URL" => "",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"MESSAGE_404" => "",
		"META_DESCRIPTION" => "-",
		"META_KEYWORDS" => "-",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Страница",
		"PROPERTY_CODE" => array("LINK","PHONE"),
		"SET_BROWSER_TITLE" => "N",
		"SET_CANONICAL_URL" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"STRICT_SECTION_CHECK" => "N",
		"USE_PERMISSIONS" => "N",
		"USE_SHARE" => "N"
	)
);?>
<section class="useful section">
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
                            <p class="paragraph">Стиральные машины, пылесосы, обогреватели, кондиционеры, холодильники – далеко не полный перечень устройств, без которых жизнь современного горожанина превратилась бы в настоящий ад. Люди просто отвыкли обходиться без всего этого. Но бытовая техника периодически выходит из строя, и встает вопрос, что с ней делать. Всем ли доступен ремонт бытовой техники? Давайте об этом поговорим.</p>
                            <p class="more"><a href="#">Подробнее...</a></p>
                        </div>
                    </div>
                    <div class="slide">
                        <div class="img">
                            <img src="<?= v::asset('images/pic/pic-4.jpg') ?>" alt="">
                        </div>
                        <div class="info">
                            <p class="title">Безопасность при ремонте бытовых приборов и техники</p>
                            <p class="paragraph">Стиральные машины, пылесосы, обогреватели, кондиционеры, холодильники – далеко не полный перечень устройств, без которых жизнь современного горожанина превратилась бы в настоящий ад. Люди просто отвыкли обходиться без всего этого. Но бытовая техника периодически выходит из строя, и встает вопрос, что с ней делать. Всем ли доступен ремонт бытовой техники? Давайте об этом поговорим.</p>
                            <p class="more"><a href="#">Подробнее...</a></p>
                        </div>
                    </div>
                    <div class="slide">
                        <div class="img">
                            <img src="<?= v::asset('images/pic/pic-4.jpg') ?>" alt="">
                        </div>
                        <div class="info">
                            <p class="title">Безопасность при ремонте бытовых приборов и техники</p>
                            <p class="paragraph">Стиральные машины, пылесосы, обогреватели, кондиционеры, холодильники – далеко не полный перечень устройств, без которых жизнь современного горожанина превратилась бы в настоящий ад. Люди просто отвыкли обходиться без всего этого. Но бытовая техника периодически выходит из строя, и встает вопрос, что с ней делать. Всем ли доступен ремонт бытовой техники? Давайте об этом поговорим.</p>
                            <p class="more"><a href="#">Подробнее...</a></p>
                        </div>
                    </div>
                </div>
                <div class="dots"></div>
            </div>
        </div>
        <div class="right">
            <div class="video-item">
                <iframe src="https://www.youtube.com/embed/hj7ZYjVFDGI?rel=0" frameborder="0" allowfullscreen></iframe>
                <p class="name">Ремонт бытовой техники на дому в Москве</p>
                <a class="btn" href="#">Смотреть все видео</a>
            </div>
        </div>
    </div>
</section>
