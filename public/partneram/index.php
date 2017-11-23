<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Сетки к бритвам, зубные насадки к электрическим зубным щеткам, запасные части к Браун, Braun, аксессуары к бытовой технике");
$APPLICATION->SetPageProperty("description", "Мы предлагаем широкий ассортимент аксессуаров  к бытовой технике  фирмы Braun (Браун)");
$APPLICATION->SetTitle("Партнерам");
$APPLICATION->SetPageProperty('layout', 'bare');

use App\App;
use App\View as v;
use Core\Underscore as _;
use App\Iblock;
use Bex\Tools\Iblock\IblockTools;

if (v::isEmpty(v::get($_REQUEST, 'tab'))) {
    LocalRedirect(v::path('partneram/account'));
}
$activeTab = $_REQUEST['tab'];
$tabs = [
    ['id' => 'account',   'name' => 'Данные сервисного центра'],
    ['id' => 'stock',     'name' => 'Складские остатки'],
    ['id' => 'feed',      'name' => 'Полезная информация'],
    ['id' => 'documents', 'name' => 'Необходимые документы'],
];
if (!in_array($activeTab, _::pluck($tabs, 'id'))) {
    LocalRedirect('/404.php');
}
?>
<section class="lk">
    <div class="section-title">
        <div class="wrap">
            <div class="section-title-block">
                <h2><? $APPLICATION->ShowTitle(false) ?></h2>
            </div>
        </div>
    </div>
    <div class="wrap">
        <div class="tab_links finger-block nav" role="tablist">
            <? foreach ($tabs as $tab): ?>
                <a data-toggle="tab"
                   href="<?= v::path('partneram/'.$tab['id']) ?>"
                   class="tab-link <?= $tab['id'] === $activeTab ? 'active' : '' ?>"
                   role="tab"><?= $tab['name'] ?></a>
            <? endforeach ?>
            <span class="finger"></span>
        </div>
        <div class="tab_blocks">
            <?
            switch ($activeTab):
                case 'account':
                    v::render('partials/partner/account.php', [], ['buffer' => false]);
                    break;
                case 'stock':
                    v::render('partials/partner/stock.php', [], ['buffer' => false]);
                    break;
                case 'feed':
                    $sections = iter\toArray(Iblock::iter(CIBlockSection::GetList([], [
                        'IBLOCK_ID' => IblockTools::find(Iblock::PARTNER_TYPE, Iblock::FEED)->id()
                    ])));
                    v::render('partials/partner/feed.php', [
                        'sectionId' => v::get($_REQUEST, 'SECTION_ID'),
                        'sectionOpts' => array_map(function ($sect) {
                            return ['value' => $sect['ID'], 'text' => $sect['NAME']];
                        }, $sections)
                    ], ['buffer' => false]);
                    break;
                case 'documents':
                    $sections = iter\toArray(Iblock::iter(CIBlockSection::GetList([], [
                        'IBLOCK_ID' => IblockTools::find(Iblock::PARTNER_TYPE, Iblock::DOCUMENTS)->id()
                    ])));
                    v::render('partials/partner/documents.php', [
                        'sectionId' => v::get($_REQUEST, 'SECTION_ID'),
                        'sectionOpts' => array_map(function ($sect) {
                            return ['value' => $sect['ID'], 'text' => $sect['NAME']];
                        }, $sections)
                    ], ['buffer' => false]);
                    break;
                default:
                    App::getInstance()->assert(false, 'illegal state');
            endswitch;
            ?>
        </div>
    </div>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>