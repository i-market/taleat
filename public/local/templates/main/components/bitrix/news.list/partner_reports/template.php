<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
use App\Report;

$getImages = function ($item) {
    $imgs = array();
    if ($item["DISPLAY_PROPERTIES"]["USER_IMGS"]["FILE_VALUE"][0]){
        $imgs = $item["DISPLAY_PROPERTIES"]["USER_IMGS"]["FILE_VALUE"];
    } else {
        if ($item["DISPLAY_PROPERTIES"]["USER_IMGS"]["FILE_VALUE"]) $imgs[0] = $item["DISPLAY_PROPERTIES"]["USER_IMGS"]["FILE_VALUE"];
    }
    return $imgs;
};
$fancyboxItems = function ($images) {
    return array_map(function ($img) {
        return ['src' => $img['SRC']];
    }, $images);
};
?>
<div class="wrap-technical-conclusion-item">
    <? foreach ($arResult['ITEMS'] as $item): ?>
        <? $images = $getImages($item) ?>
        <? $status = $item['PROPERTIES']['STATUS'] ?>
        <? $statusId = $status['VALUE_ENUM_ID'] ?>
        <? $isApproved = $statusId == Report::STATUS_APPROVED ?>
        <div class="technical-conclusion-item">
            <div class="top">
                <div class="left">
                    <span class="date"><?= $item['DISPLAY_ACTIVE_FROM'] ?></span>
                    <span class="code"><?= 'Заказ № '.$item['PROPERTIES']['NUMER']['VALUE'] ?></span>
                </div>
                <div class="right">
                    <? $class = $isApproved ? 'confirmed yes' : ($statusId == Report::STATUS_REJECTED ? 'confirmed no' : '') ?>
                    <span class="<?= $class ?>"><?= $status['VALUE'] ?></span>
                </div>
            </div>
            <? if (!v::isEmpty($item['PREVIEW_TEXT'])): ?>
                <div class="hidden-block">
                    <p class="visible-text">Замечания...</p>
                    <p class="hidden-text"><?= $item['PREVIEW_TEXT'] ?></p>
                </div>
            <? endif ?>
            <div class="bottom">
                <? // not sure what `approved` property is for. seems redundant. ?>
                <? if ($isApproved && $item['PROPERTIES']['APPROVED']['VALUE'] == 1): ?>
                    <? $fileLink = $item['DISPLAY_PROPERTIES']['FORMA']['FILE_VALUE']['SRC'] ?>
                    <p><a class="link" download href="<?= $fileLink ?>" target="_blank">Скачать форму</a></p>
                <? else: ?>
                    <p><span class="link status">Скачать форму</span></p>
                <? endif ?>

                <? if (!v::isEmpty($images)): ?>
                    <? $text = 'Чек или гарантийный талон'.(count($images) > 1 ? ' ('.count($images).')' : '') ?>
                    <? $items = v::escAttr(json_encode($fancyboxItems($images), JSON_UNESCAPED_SLASHES)) ?>
                    <p><a class="link" data-fancybox-items="<?= $items ?>" href="<?= $images[0]['SRC'] ?>" target="_blank"><?= $text ?></a></p>
                <? else: ?>
                    <p><span class="link status">Чек или гарантийный талон</span></p>
                <? endif ?>

                <? if (!v::isEmpty($item['PREVIEW_PICTURE'])): ?>
                    <p><a class="link" data-fancybox href="<?= $item['PREVIEW_PICTURE']['SRC'] ?>" target="_blank">Скриншот</a></p>
                <? else: ?>
                    <p><span class="link status">Скриншот</span></p>
                <? endif ?>
            </div>
            <? if ($statusId == Report::STATUS_REJECTED): ?>
                <a class="edit simple-btn" href="<?= v::path('partneram/reports/edit').'?id='.$item['ID'] ?>">Редактировать</a>
            <? endif ?>
        </div>
    <? endforeach ?>
</div>
<?= $arResult['NAV_STRING'] ?>
