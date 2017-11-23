<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Технические заключения на изделия Babyliss");
$APPLICATION->SetPageProperty('layout', 'bare');
$APPLICATION->SetPageProperty('body_class', 'bg');

use App\Layout;
use App\View as v;
?>

<div class="wrap">
    <? Layout::showBreadcrumbs() ?>
</div>
<section class="technical-conclusion-2">
    <div class="wrap">
        <div class="block">
            <a class="btn" href="<?= v::path('partneram/reports/new') ?>">Заполнить техническое заключение</a>
            <div class="TODO-mockup wrap-technical-conclusion-item">
                <div class="technical-conclusion-item">
                    <div class="top">
                        <div class="left">
                            <span class="date">12.12.2017</span>
                            <span class="code">Заказ № 16/000537</span>
                        </div>
                        <div class="right">
                            <span class="confirmed yes">Подтверждено</span>
                        </div>
                    </div>
                    <div class="hidden-block">
                        <p class="visible-text">Замечания...</p>
                        <p class="hidden-text">Задача организации, в особенности же сложившаяся структура организации представляет собой интересный эксперимент проверки новых предложений. Разнообразный и богатый опыт рамки и место обучения кадров играет важную роль в формировании систем массового участия. Товарищи! консультация с широким активом играет важную роль в формировании систем массового участия.</p>
                    </div>
                    <div class="bottom">
                        <p><a class="link" href="#">Скачать форму</a></p>
                        <p><a class="link status" href="#">Чек или гарантийный талон</a></p>
                        <p><a class="link status" href="#">Скриншот</a></p>
                    </div>
                </div>
                <div class="technical-conclusion-item">
                    <div class="top">
                        <div class="left">
                            <span class="date">12.12.2017</span>
                            <span class="code">Заказ № 16/000537</span>
                        </div>
                        <div class="right">
                            <span class="confirmed no">Не подтверждено</span>
                        </div>
                    </div>
                    <div class="hidden-block">
                        <p class="visible-text">Замечания...</p>
                        <p class="hidden-text">Задача организации, в особенности же сложившаяся структура организации представляет собой интересный эксперимент проверки новых предложений. Разнообразный и богатый опыт рамки и место обучения кадров играет важную роль в формировании систем массового участия. Товарищи! консультация с широким активом играет важную роль в формировании систем массового участия.</p>
                    </div>
                    <div class="bottom">
                        <p><a class="link" href="#">Скачать форму</a></p>
                        <p><a class="link" href="#">Чек или гарантийный талон</a></p>
                        <p><a class="link" href="#">Скриншот</a></p>
                    </div>
                </div>
            </div>
            <div class="TODO-mockup paginator">
                <div class="paginator-inner">
                    <a class="prev" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="11" height="18" viewBox="0 0 11 18">
                            <defs>
                                <style>
                                    .cls-1 {
                                        fill: #214385;
                                        fill-rule: evenodd;
                                    }
                                </style>
                            </defs>
                            <path id="arrow-left.svg" class="cls-1" d="M313,660l9-9h2l-9,9h-2Zm0,0,9,9h2l-9-9h-2Z" transform="translate(-313 -651)"/>
                        </svg></a>
                    <ul>
                        <li><a href="#">1</a></li>
                        <li><a href="#" class="active">2</a></li>
                        <li><a href="#">3</a></li>
                    </ul>
                    <a class="next" href="#">
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
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>