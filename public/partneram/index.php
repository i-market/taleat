<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Сетки к бритвам, зубные насадки к электрическим зубным щеткам, запасные части к Браун, Braun, аксессуары к бытовой технике");
$APPLICATION->SetPageProperty("description", "Мы предлагаем широкий ассортимент аксессуаров  к бытовой технике  фирмы Braun (Браун)");
$APPLICATION->SetTitle("Партнерам");
$APPLICATION->SetPageProperty('layout', 'bare');

use App\View as v;

// TODO active tab
$activeTab = $_REQUEST['tab'] ?: 'account';
$tabs = [
    ['id' => 'account', 'name' => 'Данные сервисного центра'],
    ['id' => 'stock', 'name' => 'Складские остатки'],
    ['id' => 'feed', 'name' => 'Полезная информация'],
    ['id' => 'documents', 'name' => 'Необходимые документы'],
];
?>
<? // TODO tmp ?>
    <style>
        <? foreach ($tabs as $t): ?>
            <?= '.'.$t['id'] ?> {
                display: none;
            }
        <? endforeach ?>
        <?= '.'.$activeTab ?> {
            display: block !important;
        }
    </style>
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
            <div class="account">
                <?
                use App\Components;
                ?>
                <div class="tabs-inner">
                    <div class="data-center">
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
                        <? Components::showNewsletterSub() ?>
                        <div class="TODO-mockup">
                            <span class="simple-btn change-password">Сменить пароль</span>
                            <div class="change-password-hidden">
                                <input type="password" class="input" placeholder="Старый пароль">
                                <input type="password" class="input" placeholder="Новый пароль">
                                <input type="password" class="input" placeholder="Новый пароль еще раз">
                                <button class="yellow-btn">Сохранить изменения</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="stock">
                <?
                use Core\Util;
                ?>
                <div class="tabs-inner">
                    <div class="stock-balance">
                        <div class="editable-area text">
                            <? $APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                Array(
                                    "AREA_FILE_SHOW" => "file",
                                    "PATH" => v::includedArea('partner/stock/text.php')
                                )
                            ); ?>
                        </div>
                        <? $files = [
                            // uploaded by a third-party
                            'Braun'     => '/partneram/ostatki_V/ostatki_braun1.xls',
                            "De'Longhi" => '/partneram/ostatki_V/ostatki_braun2.xls',
                            'Babyliss'  => '/partneram/ostatki_V/ostatki_babyliss.xls'
                        ] ?>
                        <? foreach ($files as $brand => $path): ?>
                            <? list($_, $ext) = Util::splitFileExtension($path) ?>
                            <p class="line">
                                <a class="download-btn"
                                    <?= v::attrs(v::docLinkAttrs($ext)) ?>
                                   href="<?= $path ?>">Скачать остатки<span><?= $brand ?></span></a>
                            </p>
                        <? endforeach ?>
                    </div>
                </div>
            </div>
            <div class="feed">
                <div class="TODO-mockup tabs-inner-sort">
                    <? // TODO styles ?>
                    <span class="text" style="white-space: nowrap">Для бренда:</span>
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
            <div class="documents">
                <?
                // TODO
                use App\Iblock;
                use Bex\Tools\Iblock\IblockTools;
                use Core\Underscore as _;

                $sectionId = $_REQUEST['SECTION_ID'];
                $sections = iter\toArray(Iblock::iter(CIBlockSection::GetList([], [
                    'IBLOCK_ID' => IblockTools::find(Iblock::PARTNER_TYPE, Iblock::DOCUMENTS)->id()
                ])));
                $section = _::find($sections, function ($sect) use ($sectionId) {
                    return $sect['ID'] == $sectionId;
                })
                ?>
                <? v::render('partials/partner/documents.php', [
                    'section' => $section,
                    'sectionOpts' => array_map(function ($sect) {
                        return ['value' => $sect['ID'], 'text' => $sect['NAME']];
                    }, $sections)
                ], ['buffer' => false]) ?>
            </div>
        </div>
    </div>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>