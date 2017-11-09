<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @global $APPLICATION
 * @global $USER
 */

use App\App;
use App\Auth;
use Bitrix\Main\Page\Asset;
use App\View as v;
use App\Layout;

// TODO move out of the template
Auth::restrictAccess();
App::getInstance()->assert(!($_REQUEST["auth"]=="Войти"), 'legacy');
App::getInstance()->assert(!isset($_POST["AUTH_FORM_PARTNER"]), 'legacy');

extract(App::getInstance()->layoutContext(), EXTR_SKIP);

if ($isAjax) {
    // skip the whole thing for ajax requests
    return;
}
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
<body data-anchor="top">
<? $APPLICATION->ShowPanel() ?>
<div id="global-loader">Загрузка...</div>
<header class="header">
    <div class="menu-hidden">
        <div class="menu-hidden-close"></div>
        <div class="menu-hidden-inner">
            <?= v::render('partials/layout/auth_phone.php', ['auth' => $auth]) ?>
            <ul class="TODO-mockup">
                <li><a href="#" class="active"><span>Оплата и доставка</span></a></li>
                <li><a href="#"><span>Партнёрам</span></a></li>
                <li><a href="#"><span>Новости</span></a></li>
                <li><a href="#"><span>Отзывы</span></a></li>
                <li><a href="#"><span>Контакты</span></a></li>
                <li><a href="#"><span>Полезное</span></a></li>
                <li><a href="#"><span>Видео</span></a></li>
            </ul>
            <span class="TODO-mockup re-call"><span>Написать нам</span></span>
            <div class="menu-hidden-phones">
                <a href="tel:+7(495)437-23-29"><span>+7(495)</span>437-23-29</a>
                <a href="tel:+7(495)437-23-29"><span>+7(495)</span>437-23-29</a>
            </div>
            <div class="TODO-mockup menu-hidden-info">
                <p>e-mail: asn@taleat.ru</p>
                <p>Адрес фактический: 127473, г. Москва, ул. Селезневская,<br>д. 30 корп. 1</p>
            </div>
        </div>
    </div>
    <div class="header-top">
        <div class="wrap">
            <span class="service-center-link">Авторизированный сервисный центр</span>
            <div class="header-top-info">
                <nav class="TODO-mockup main-menu">
                    <ul>
                        <li><a href="#">Оплата и доставка</a></li>
                        <li><a href="/partneram/">Партнёрам</a></li>
                        <li><a href="#">Новости</a></li>
                        <li><a href="#">Отзывы</a></li>
                        <li><a href="#">Контакты</a></li>
                        <li><a href="#">Полезное</a></li>
                        <li><a href="#">Видео</a></li>
                    </ul>
                </nav>
                <?= v::render('partials/layout/auth_desktop.php', ['auth' => $auth]) ?>
            </div>
        </div>
    </div>
    <div class="header-middle">
        <div class="wrap">
            <a class="logo" href="<?= v::path('/') ?>">
                <img src="<?= v::asset('images/ico/logo.png') ?>" alt="">
            </a>
            <span class="open-header-form"></span>
            <div class="TODO-mockup header-middle-info">
                <form action="<?= v::path('search/index.php') ?>" method="get" id="" class="header-middle-serch">
                    <input name="q" type="text" placeholder="Найти" autocomplete="off">
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
                <span class="absurd">
                  <span class="absurd-desktop"></span>
                  <span class="absurd-hidden"></span>
                </span>
                <a class="catalog-link" href="<?= v::path('catalog') ?>">Каталог <span class="hidden">товаров</span></a>
            </div>
            <div class="header-bottom-right">
                <a class="service-maintenance-link" href="#">Сервис<span class="hidden">ное обслуживание</span></a>
                <span id="mini-cart">
                    <? Layout::showHeaderCart() ?>
                </span>
            </div>
        </div>
    </div>
</header>
<main class="content">
    <span class="scroll-top" data-href="top"></span>
    <? v::showForLayout('default', function () { ?>
        <? Layout::showMegaMenu('content-menu-block--pages') ?>
        <? Layout::showDefaultPageWrapper('header') ?>
    <? }) ?>
    <? v::showForLayout('bare', function () { ?>
        <? Layout::showMegaMenu('content-menu-block--pages') ?>
    <? }) ?>
    <? v::showForLayout('homepage', function () { ?>
        <? Layout::showMegaMenu() ?>
    <? }) ?>
