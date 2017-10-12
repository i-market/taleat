<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @global $APPLICATION
 * @global $USER
 */

use App\App;
use Bitrix\Main\Page\Asset;
use App\View as v;

extract(App::getInstance()->layoutContext(), EXTR_SKIP);

$assets = App::assets();
$asset = Asset::getInstance();
$asset->setJsToBody(true);
if (App::useBitrixAsset()) {
    foreach ($assets['styles'] as $path) {
        $asset->addCss($path);
    }
    foreach ($assets['scripts'] as $path) {
        $asset->addJs($path);
    }
}
?>
<!DOCTYPE html>
<html lang="<?= LANGUAGE_ID ?>">
<head>
    <? $APPLICATION->ShowHead() ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <title><? $APPLICATION->ShowTitle() ?></title>
    <? if (!App::useBitrixAsset()): ?>
        <? foreach ($assets['styles'] as $path): ?>
            <link rel="stylesheet" media="screen" href="<?= $path ?>">
        <? endforeach ?>
    <? endif ?>
    <!--[if gte IE 9]>
    <style type="text/css">
        .gradient {
            filter: none;
        }
    </style>
    <![endif]-->
</head>
<body>
<? $APPLICATION->ShowPanel() ?>
<header class="header">
    <div class="menu-hidden">
        <div class="menu-hidden-close"></div>
        <div class="menu-hidden-inner">
            <div class="menu-hidden-sign-in">
                <? if ($USER->IsAuthorized()): ?>
                    <a href="<?= $auth['profileLink'] ?>">Личный кабинет</a>
                    <span>/</span>
                    <a href="<?= $auth['logoutLink'] ?>">Выйти</a>
                <? else: ?>
                    <a href="<?= $auth['loginLink'] ?>">Вход</a>
                    <span>/</span>
                    <a href="<?= $auth['registerLink'] ?>">Регистрация</a>
                <? endif ?>
            </div>
            <ul>
                <li><a href="#" class="active"><span>Оплата и доставка</span></a></li>
                <li><a href="#"><span>Партнёрам</span></a></li>
                <li><a href="#"><span>Новости</span></a></li>
                <li><a href="#"><span>Отзывы</span></a></li>
                <li><a href="#"><span>Контакты</span></a></li>
                <li><a href="#"><span>Полезное</span></a></li>
                <li><a href="#"><span>Видео</span></a></li>
            </ul>
            <span class="re-call"><span>Написать нам</span></span>
            <div class="menu-hidden-phones">
                <a href="tel:+7(495)437-23-29"><span>+7(495)</span>437-23-29</a>
                <a href="tel:+7(495)437-23-29"><span>+7(495)</span>437-23-29</a>
            </div>
            <div class="menu-hidden-info">
                <p>e-mail: asn@taleat.ru</p>
                <p>Адрес фактический: 127473, г. Москва, ул. Селезневская,<br>д. 30 корп. 1</p>
            </div>
        </div>
    </div>
    <div class="header-top">
        <div class="wrap">
            <a class="service-center-link" href="#">Авторизированный сервисный центр</a>
            <div class="header-top-info">
                <nav class="main-menu">
                    <ul>
                        <li><a href="#">Оплата и доставка</a></li>
                        <li><a href="#">Партнёрам</a></li>
                        <li><a href="#">Новости</a></li>
                        <li><a href="#">Отзывы</a></li>
                        <li><a href="#">Контакты</a></li>
                        <li><a href="#">Полезное</a></li>
                        <li><a href="#">Видео</a></li>
                    </ul>
                </nav>
                <div class="sig-in">
                    <? if ($USER->IsAuthorized()): ?>
                        <? // TODO ux: show username or something ?>
                        <a class="sig-in-link" href="<?= $auth['profileLink'] ?>">Личный кабинет</a>
                        <span class="separator">/</span>
                        <a class="sig-in-link" href="<?= $auth['logoutLink'] ?>">Выйти</a>
                    <? else: ?>
                        <a class="sig-in-link" href="<?= $auth['loginLink'] ?>">Вход</a>
                        <span class="separator">/</span>
                        <a class="sig-in-link" href="<?= $auth['registerLink'] ?>">Регистрация</a>
                    <? endif ?>
                </div>
            </div>
        </div>
    </div>
    <div class="header-middle">
        <div class="wrap">
            <a class="logo" href="<?= v::path('/') ?>">
                <img src="<?= v::asset('images/ico/logo.png') ?>" alt="">
            </a>
            <span class="open-header-form"></span>
            <div class="header-middle-info">
                <form action="" method="post" id="" class="header-middle-serch">
                    <input type="text" placeholder="Найти">
                    <button type="submit"></button>
                </form>
                <span class="header-re-call" data-modal="re-call">Написать нам</span>
                <div class="header-middle-phones">
                    <a href="tel:+7(495)437-23-29"><span>+7(495)</span>437-23-29</a>
                    <a href="tel:+7(495)437-23-29"><span>+7(495)</span>437-23-29</a>
                </div>
            </div>
            <span class="header-middle-hamburger"></span>
        </div>
    </div>
    <div class="header-bottom">
        <div class="wrap">
            <div class="header-bottom-left">
                <span class="absurd"><span class="absurd-hidden"></span></span>
                <a class="catalog-link" href="<?= v::path('catalog') ?>">Каталог <span class="hidden">товаров</span></a>
            </div>
            <div class="header-bottom-right">
                <a class="service-maintenance-link" href="#">Сервис<span class="hidden">ное обслуживание</span></a>
                <?$APPLICATION->IncludeComponent(
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
                );?>
            </div>
        </div>
    </div>
</header>
<main class="content">
    <div class="content-menu-block">
        <div class="wrap">
            <?$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "header_menu", Array(
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
            ),
                false
            );?>
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
