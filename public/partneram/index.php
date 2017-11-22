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
                <h2><? $APPLICATION->ShowTitle(false) ?></h2>
            </div>
        </div>
    </div>
    <div class="wrap">
        <div class="tab_links finger-block" role="tablist">
            <? foreach ($tabs as $tab): ?>
                <a data-toggle="tab"
                   href="<?= v::path('partneram/'.$tab['id']) ?>"
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
                <?
                // TODO
                use App\Iblock;
                use Bex\Tools\Iblock\IblockTools;

                $sections = iter\toArray(Iblock::iter(CIBlockSection::GetList([], [
                    'IBLOCK_ID' => IblockTools::find(Iblock::PARTNER_TYPE, Iblock::FEED)->id()
                ])));
                ?>
                <? v::render('partials/partner/feed.php', [
                    'sectionId' => v::get($_REQUEST, 'SECTION_ID'),
                    'sectionOpts' => array_map(function ($sect) {
                        return ['value' => $sect['ID'], 'text' => $sect['NAME']];
                    }, $sections)
                ], ['buffer' => false]) ?>
            </div>
            <div class="documents">
                <?
                // TODO

                $sections = iter\toArray(Iblock::iter(CIBlockSection::GetList([], [
                    'IBLOCK_ID' => IblockTools::find(Iblock::PARTNER_TYPE, Iblock::DOCUMENTS)->id()
                ])));
                ?>
                <? v::render('partials/partner/documents.php', [
                    'sectionId' => v::get($_REQUEST, 'SECTION_ID'),
                    'sectionOpts' => array_map(function ($sect) {
                        return ['value' => $sect['ID'], 'text' => $sect['NAME']];
                    }, $sections)
                ], ['buffer' => false]) ?>
            </div>
        </div>
    </div>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>