<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Сетки к бритвам, зубные насадки к электрическим зубным щеткам, запасные части к Браун, Braun, аксессуары к бытовой технике");
$APPLICATION->SetPageProperty("description", "Мы предлагаем широкий ассортимент аксессуаров  к бытовой технике  фирмы Braun (Браун)");
$APPLICATION->SetTitle("Партнерам");
$APPLICATION->SetPageProperty('layout', 'bare');

use App\View as v;

// TODO active tab
$activeTab = 'account';
$tabs = [
    ['id' => 'account', 'name' => 'Данные сервисного центра'],
    ['id' => 'ostatki', 'name' => 'Складские остатки'],
    ['id' => 'block-3', 'name' => 'Полезная информация'],
    ['id' => 'block-4', 'name' => 'Необходимые документы'],
];
?>

<section class="lk">
    <div class="section-title">
        <div class="wrap">
            <div class="section-title-block">
                <? // TODO mockup: "Личный кабинет" ?>
                <h2 class="TODO-mockup"><? $APPLICATION->ShowTitle(false) ?></h2>
            </div>
        </div>
    </div>
    <div class="wrap">
        <div class="tab_links finger-block" role="tablist">
            <? foreach ($tabs as $tab): ?>
                <a data-toggle="tab"
                   href="<?= v::path('./'.$tab['id']) ?>"
                   class="tab-link <?= $tab['id'] === $activeTab ? 'active' : '' ?>"
                   role="tab"><?= $tab['name'] ?></a>
            <? endforeach ?>
            <span class="finger"></span>
        </div>
        <div class="tab_blocks">
            <div data-tabContent="account">
                <div class="tabs-inner">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:main.profile",
                        "partner",
                        Array(
                            "AJAX_MODE" => "N",
                            "AJAX_OPTION_ADDITIONAL" => "",
                            "AJAX_OPTION_HISTORY" => "N",
                            "AJAX_OPTION_JUMP" => "N",
                            "AJAX_OPTION_STYLE" => "N",
                            "CHECK_RIGHTS" => "N",
                            "SEND_INFO" => "N",
                            "SET_TITLE" => "N",
                            "USER_PROPERTY" => array(),
                            "USER_PROPERTY_NAME" => ""
                        )
                    ); ?>
                </div>
            </div>
            <div data-tabContent="ostatki">
                <div class="tabs-inner">
                    <div class="stock-balance">
                        <p class="text">Обновление остатков склада происходит ежедневно в конце дня. Если у Вас возникли какие-либо вопросы, свяжитесь с нами:
                            <span><a href="mailto:asn@taleat.ru">asn@taleat.ru</a></span> C уважением, "ООО Талеат-Сервис"</p>
                        <p class="line"><a class="download-btn" href="#">Скачать остатки<span>BABYLISS</span></a></p>
                        <p class="line"><a class="download-btn" href="#">Скачать остатки<span>BABYLISS</span></a></p>
                        <p class="line"><a class="download-btn" href="#">Скачать остатки<span>braun</span></a></p>
                        <p class="line"><a class="download-btn" href="#">Скачать остатки<span>BABYLISS</span></a></p>
                    </div>
                </div>
            </div>
            <div data-tabContent="block-3">
                <div class="tabs-inner-sort">
                    <span class="text">Сортировать <span class="hidden">по брендам:</span></span>
                    <select name="" id="">
                        <option value="">Все бренды</option>
                        <option value="">Бренд 1</option>
                        <option value="">Бренд 2</option>
                        <option value="">Бренд 3</option>
                        <option value="">Бренд 4</option>
                    </select>
                </div>
                <div class="tabs-inner">
                    <div class="helpful-information">
                        <div class="item">
                            <p class="top">
                                <span class="date">09.07.2017</span>
                                <a href="#" class="brand">Braun</a>
                            </p>
                            <p class="text"><a href="#">Безопасность при ремонте бытовых приборов и техники</a></p>
                        </div>
                        <div class="item">
                            <p class="top">
                                <span class="date">09.07.2017</span>
                                <a href="#" class="brand">Braun</a>
                            </p>
                            <p class="text"><a href="#">Безопасность при ремонте бытовых приборов и техники</a></p>
                        </div>
                        <div class="item">
                            <p class="top">
                                <span class="date">09.07.2017</span>
                                <a href="#" class="brand">Braun</a>
                            </p>
                            <p class="text"><a href="#">Безопасность при ремонте бытовых приборов и техники</a></p>
                        </div>
                        <div class="item">
                            <p class="top">
                                <span class="date">09.07.2017</span>
                                <a href="#" class="brand">Braun</a>
                            </p>
                            <p class="text"><a href="#">Безопасность при ремонте бытовых приборов и техники</a></p>
                        </div>
                        <div class="item">
                            <p class="top">
                                <span class="date">09.07.2017</span>
                                <a href="#" class="brand">Braun</a>
                            </p>
                            <p class="text"><a href="#">Безопасность при ремонте бытовых приборов и техники</a></p>
                        </div>
                        <div class="item">
                            <p class="top">
                                <span class="date">09.07.2017</span>
                                <a href="#" class="brand">Braun</a>
                            </p>
                            <p class="text"><a href="#">Безопасность при ремонте бытовых приборов и техники</a></p>
                        </div>
                    </div>
                    <div class="paginator">
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
            <div data-tabContent="block-4">
                <div class="tabs-inner-sort">
                    <span class="text">Сортировать <span class="hidden">по брендам:</span></span>
                    <select name="" id="">
                        <option value="">Все бренды</option>
                        <option value="">Бренд 1</option>
                        <option value="">Бренд 2</option>
                        <option value="">Бренд 3</option>
                        <option value="">Бренд 4</option>
                    </select>
                </div>
                <div class="tabs-inner">
                    <div class="required-documents">
                        <a class="download-btn download-btn--small" href="#">Техническое заключение<span>BABYLISS</span></a>
                        <div class="wrap-documents">
                            <div class="item">
                                <a href="#" class="item-link pdf">
                                    <span class="size">960kb</span>
                                    <span class="name">Договор на оказание услуг</span>
                                </a>
                                <a class="brand" href="#">Braun</a>
                            </div>
                            <div class="item">
                                <a href="#" class="item-link doc">
                                    <span class="size">960kb</span>
                                    <span class="name">Договор на оказание услуг</span>
                                </a>
                                <a class="brand" href="#">Braun</a>
                            </div>
                            <div class="item">
                                <a href="#" class="item-link xls">
                                    <span class="size">960kb</span>
                                    <span class="name">Договор на оказание услуг</span>
                                </a>
                                <a class="brand" href="#">Braun</a>
                            </div>
                            <div class="item">
                                <a href="#" class="item-link rar">
                                    <span class="size">960kb</span>
                                    <span class="name">Договор на оказание услуг</span>
                                </a>
                                <a class="brand" href="#">Braun</a>
                            </div>
                            <div class="item">
                                <a href="#" class="item-link zip">
                                    <span class="size">960kb</span>
                                    <span class="name">Договор на оказание услуг</span>
                                </a>
                                <a class="brand" href="#">Braun</a>
                            </div>
                            <div class="item">
                                <a href="#" class="item-link pdf">
                                    <span class="size">960kb</span>
                                    <span class="name">Договор на оказание услуг</span>
                                </a>
                                <a class="brand" href="#">Braun</a>
                            </div>
                            <div class="item">
                                <a href="#" class="item-link doc">
                                    <span class="size">960kb</span>
                                    <span class="name">Договор на оказание услуг</span>
                                </a>
                                <a class="brand" href="#">Braun</a>
                            </div>
                            <div class="item">
                                <a href="#" class="item-link xls">
                                    <span class="size">960kb</span>
                                    <span class="name">Договор на оказание услуг</span>
                                </a>
                                <a class="brand" href="#">Braun</a>
                            </div>
                        </div>
                        <div class="paginator">
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
            </div>
        </div>
    </div>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>