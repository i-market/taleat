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
                            <div class="rsc-inner">
                                <div class="contacts-info">
                                    <? // <p class="contacts-name">ООО “Селена - сервис”</p> ?>
                                    <div class="editable-area contacts-text">
                                        <?= $service['~PREVIEW_TEXT'] ?>
                                    </div>
                                </div>
                                <?/*
                                <div class="contacts-bottom">
                                    <div class="grid">
                                        <div class="col col-2">
                                            <span class="simple-btn"><span>Посмотреть на большой карте</span></span>
                                        </div>
                                        <div class="col col-2">
                                            <span class="simple-btn"><span>Скачать схему проезда</span></span>
                                        </div>
                                    </div>
                                </div>
                                */?>
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