<?php

namespace App;

class Layout {
    static function showHeaderCart() {
        global $APPLICATION;
        $APPLICATION->IncludeComponent(
            "bitrix:sale.basket.basket.line",
            "header",
            array(
                "PATH_TO_BASKET" => SITE_DIR."personal/cart/",
                "PATH_TO_PERSONAL" => SITE_DIR."personal/",
                "SHOW_PERSONAL_LINK" => "N",
                "COMPONENT_TEMPLATE" => "basket",
                "PATH_TO_ORDER" => SITE_DIR."personal/order/make/",
                "SHOW_NUM_PRODUCTS" => "Y",
                "SHOW_TOTAL_PRICE" => "Y",
                "SHOW_EMPTY_VALUES" => "Y",
                "SHOW_AUTHOR" => "N",
                "PATH_TO_REGISTER" => SITE_DIR."login/",
                "PATH_TO_PROFILE" => SITE_DIR."personal/",
                "SHOW_PRODUCTS" => "N",
                "POSITION_FIXED" => "N",
                "HIDE_ON_BASKET_PAGES" => "N"
            ),
            false
        );
    }

    static function showMegaMenu($class = '') {
        ?>
        <div class="content-menu-block <?= $class ?>">
            <div class="wrap">
                <? self::showHeaderMenu() ?>
                <div class="content-menu-items">
                    <a class="content-menu-item shop" href="#">
                        <strong class="title">Магазин</strong>
                        <p class="text">Интернет-магазин для заказа запасных частей и аксеcсуаров к бытовой технике фирм Braun и Babyliss PARIS</p>
                    </a>
                    <a class="content-menu-item services" href="#">
                        <strong class="title">Сервисное обслуживание</strong>
                        <p class="text">Найти ближайший к Вам сервисный центр Braun или Babyliss PARIS</p>
                    </a>
                    <a class="content-menu-item reception-point" href="#">
                        <strong class="title">Приемные пункты</strong>
                        <p class="text">Наши приемные пункты в ремонт, а так же пункты продажи запасных частей и аксессуаров к бытовой технике фирм Braun и Babyliss PARIS</p>
                    </a>
                </div>
            </div>
        </div>
        <?
    }

    static function showCatalogWrapper($fragment) {
        ?>
        <? if ($fragment === 'header'): ?>
            <section class="catalog-pages bg">
                <div class="wrap">
                    <? self::showHeaderMenu('content-menu--pages') ?>
        <? elseif ($fragment === 'footer'): ?>
                </div>
            </section>
        <? endif ?>
        <?
    }

    static function showDefaultPageWrapper($fragment) {
        global $APPLICATION;
        ?>
        <? if ($fragment === 'header'): ?>
            <section class="section">
                <div class="section-title">
                    <div class="wrap">
                        <div class="section-title-block">
                            <h2><?= $APPLICATION->GetTitle(false) ?></h2>
                        </div>
                    </div>
                </div>
                <div class="wrap">
                    <div class="editable-area editable-area--full wrap-min">
        <? elseif ($fragment === 'footer'): ?>
                    </div>
                </div>
            </section>
        <? endif ?>
        <?
    }

    static function showHeaderMenu($class = '') {
        global $APPLICATION;
        $APPLICATION->IncludeComponent("bitrix:catalog.section.list", "header_menu", Array(
            "IBLOCK_TYPE" => "catalog",	// Тип инфоблока
            "IBLOCK_ID" => "3",	// Инфоблок
            "SECTION_ID" => "",	// ID раздела
            "SECTION_CODE" => "",	// Код раздела
            "COUNT_ELEMENTS" => "N",	// Показывать количество элементов в разделе
            "TOP_DEPTH" => "2",	// Максимальная отображаемая глубина разделов
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
            "CLASS" => $class
        ),
            false
        );
    }
}