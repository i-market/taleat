<?
require ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Сетки к бритвам, зубные насадки к электрическим зубным щеткам, запасные части к Браун, Braun, аксессуары к бытовой технике");
$APPLICATION->SetPageProperty("description", "Мы предлагаем широкий ассортимент аксессуаров  к бытовой технике  фирмы Braun (Браун)");
$APPLICATION->SetTitle("Региональные сервис-центры Браун BRAUN и Babyliss PARIS");
$APPLICATION->SetPageProperty("layout", "bare");

use App\View as v;
use App\Region;

$ctx = Region::context($_REQUEST);
?>

<section class="regional-service-centers">
    <div class="section-title">
        <div class="wrap">
            <div class="section-title-block">
                <h2><? $APPLICATION->ShowTitle(false) ?></h2>
            </div>
        </div>
    </div>
    <div class="wrap">
        <div class="editable-area default-page">
            <? $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                Array(
                    "AREA_FILE_SHOW" => "file",
                    "PATH" => v::includedArea('region/top_text.php')
                )
            ); ?>
        </div>
        <div class="grid">
            <? foreach ($ctx['items'] as $item): ?>
                <? $brand = $item['brand'] ?>
                <div class="col col-3">
                    <div class="rsc-item">
                        <p class="title"><?= $brand['VALUE'] ?></p>
                        <div class="editable-area description">
                            <? $APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                Array(
                                    "AREA_FILE_SHOW" => "file",
                                    "PATH" => $item['brand_desc_path']
                                )
                            ); ?>
                        </div>
                    </div>
                    <select class="city" data-brand="<?= $brand['ID'] ?>">
                        <option value="">Выберите регион...</option>
                        <? foreach ($item['cities'] as $city): ?>
                            <? $selected = v::get($ctx['selected'], $brand['ID']) == $city['ID'] ?>
                            <option value="<?= $city['ID'] ?>" <?= $selected ? 'selected' : '' ?>><?= $city['NAME'] ?></option>
                        <? endforeach ?>
                    </select>
                    <div class="services">
                        <? foreach (v::get($item, 'services', []) as $service): ?>
                            <? $location = $service['PROPERTIES']['LOCATION']['~VALUE'] ?>
                            <? $fileId = $service['PROPERTIES']['DIRECTIONS']['~VALUE'] ?>
                            <? $directions = $fileId ? CFile::GetFileArray($fileId) : null ?>
                            <div class="rsc-inner">
                                <div class="contacts-info">
                                    <? // <p class="contacts-name">ООО “Селена - сервис”</p> ?>
                                    <div class="editable-area contacts-text">
                                        <?= $service['~PREVIEW_TEXT'] ?>
                                    </div>
                                </div>
                                <div class="contacts-bottom">
                                    <div class="grid">
                                        <? if (!v::isEmpty($location)): ?>
                                            <div class="col col-2">
                                                <?
                                                // TODO better map
                                                list($lat, $lng) = explode(',', $location);
                                                $point = $lng.','.$lat;
                                                $query = http_build_query([
                                                    'mode' => 'whatshere',
                                                    'whatshere[point]' => $point,
                                                    'll' => $point,
                                                    'zoom' => 14
                                                ]);
                                                ?>
                                                <? $link = 'https://yandex.ru/maps/?'.$query ?>
                                                <a href="<?= $link ?>" target="_blank" class="simple-btn"><span>Посмотреть на большой карте</span></a>
                                            </div>
                                            <div class="col col-2 hidden-lg-up">
                                                <a href="<?= "geo:{$lat},{$lng}" ?>" class="simple-btn"><span>Перейти в Яндекс.Навигатор</span></a>
                                            </div>
                                        <? endif ?>
                                        <? if (!v::isEmpty($directions)): ?>
                                            <div class="col col-2">
                                                <? list($_, $ext) = \Core\Util::splitFileExtension($directions['FILE_NAME']) ?>
                                                <a href="<?= $directions['SRC'] ?>" <?= v::attrs(v::docLinkAttrs($ext)) ?> class="simple-btn"><span>Скачать схему проезда</span></a>
                                            </div>
                                        <? endif ?>
                                    </div>
                                </div>
                            </div>
                        <? endforeach ?>
                    </div>
                </div>
            <? endforeach ?>
        </div>
    </div>
    <div class="wrap">
        <?$APPLICATION->IncludeComponent("bitrix:main.include",
            ".default",
            Array(
                "AREA_FILE_SHOW"=>"file",
                "PATH"=>"/include_area/region.php",
                "EDIT_TEMPLATE"=>"standard.php"),
            false
        );?>
    </div>
</section>

<?require ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>