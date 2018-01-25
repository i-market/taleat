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
use Core\Session;

App::getInstance()->assert(!($_REQUEST["auth"]=="Войти"), 'legacy');
App::getInstance()->assert(!isset($_POST["AUTH_FORM_PARTNER"]), 'legacy');

// bring context variables into scope
extract(App::getInstance()->layoutContext(), EXTR_SKIP);

Session::process();

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
<body data-anchor="top" class="<? $showBodyClass() ?>">
<? $APPLICATION->ShowPanel() ?>
<div id="global-loader">Загрузка...</div>
<? foreach (Session::getFlash() as $msg): ?>
    <div class="flash-message <?= v::get($msg, 'type') ?>"><?= $msg['text'] ?></div>
<? endforeach ?>
<header class="header">
    <div class="menu-hidden">
        <div class="menu-hidden-close"></div>
        <div class="menu-hidden-inner">
            <?= v::render('partials/layout/auth_phone.php', ['auth' => $auth]) ?>
            <? $APPLICATION->IncludeComponent(
                "bitrix:menu",
                "mobile",
                Array(
                    "ALLOW_MULTI_SELECT" => "N",
                    "CHILD_MENU_TYPE" => "left",
                    "DELAY" => "N",
                    "MAX_LEVEL" => "1",
                    "MENU_CACHE_GET_VARS" => array(""),
                    "MENU_CACHE_TIME" => "3600",
                    "MENU_CACHE_TYPE" => "N",
                    "MENU_CACHE_USE_GROUPS" => "Y",
                    "ROOT_MENU_TYPE" => "top",
                    "USE_EXT" => "Y"
                )
            ); ?>
            <span class="re-call" data-modal="contact-modal"><span>Написать нам</span></span>
            <div class="menu-hidden-phones">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => v::includedArea('contact_info/header_mobile_tel.php')
                    )
                ); ?>
            </div>
            <div class="menu-hidden-info">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => v::includedArea('contact_info/header_mobile_info.php')
                    )
                ); ?>
            </div>
        </div>
    </div>
    <div class="header-top">
        <div class="wrap">
            <span class="service-center-link">Авторизированный сервисный центр</span>
            <div class="header-top-info">
                <nav class="main-menu">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:menu",
                        "header",
                        Array(
                            "ALLOW_MULTI_SELECT" => "N",
                            "CHILD_MENU_TYPE" => "left",
                            "DELAY" => "N",
                            "MAX_LEVEL" => "1",
                            "MENU_CACHE_GET_VARS" => array(""),
                            "MENU_CACHE_TIME" => "3600",
                            "MENU_CACHE_TYPE" => "N",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "ROOT_MENU_TYPE" => "top",
                            "USE_EXT" => "Y"
                        )
                    ); ?>
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
            <div class="header-middle-info">
                <form action="<?= v::path('search') ?>" method="get" class="header-middle-serch">
                    <input name="q" type="text" placeholder="Найти" autocomplete="off">
                    <button type="submit"></button>
                </form>
                <span class="header-re-call" data-modal="contact-modal">Написать нам</span>
                <div class="header-middle-phones">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => v::includedArea('contact_info/header_desktop_tel.php')
                        )
                    ); ?>
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
                <a class="service-maintenance-link" href="<?= v::path('region') ?>">Сервис<span class="hidden">ное обслуживание</span></a>
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
        <?
        $class = CSite::InDir(v::path('auth')) ? 'default-page--has-forms' : '';
        Layout::showMegaMenu('content-menu-block--pages');
        Layout::showDefaultPageWrapper('header', $class);
        ?>
    <? }) ?>
    <? v::showForLayout('bare', function () { ?>
        <? Layout::showMegaMenu('content-menu-block--pages') ?>
    <? }) ?>
    <? v::showForLayout('homepage', function () { ?>
        <? Layout::showMegaMenu() ?>
    <? }) ?>
    <? v::showForLayout('personal', function () { ?>
        <? Layout::showMegaMenu('content-menu-block--pages') ?>
        <? Layout::showPersonalPageWrapper('header') ?>
    <? }) ?>

    <? // TODO refactor: auth checks shouldn't be done from a template ?>
    <? // important ?>
    <? Auth::restrictAccess() ?>
