<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
$mapId = function ($idx) { return "contacts-map-{$idx}"; }
?>
<? // TODO refactor: yandex maps ?>
<script>
  window._ymapsCallback = function () {
    <? foreach ($arResult['ITEMS'] as $idx => $item): ?>
        <? $latLong = $item['LAT_LONG'] ?>
        <? if ($latLong === null) continue; // TODO validate beforehand ?>
        (function () {
          var latLong = <?= json_encode([$latLong['lat'], $latLong['long']]) ?>;
          var zoom = 14;
          var map = new ymaps.Map($('<?= '#'.$mapId($idx) ?>')[0], {
            zoom: zoom,
            center: latLong,
            controls: ['zoomControl']
          });
          map.behaviors.disable('scrollZoom');
          var marker = new ymaps.Placemark(latLong);
          map.geoObjects.add(marker);
        })();
    <? endforeach ?>
  };
</script>
<script src="https://api-maps.yandex.ru/2.1/?lang=ru-RU&onload=_ymapsCallback" type="text/javascript"></script>
<div class="grid">
    <? foreach ($arResult['ITEMS'] as $idx => $item): ?>
        <? $directions = v::get($item, 'DISPLAY_PROPERTIES.DIRECTIONS.FILE_VALUE.SRC') ?>
        <? $description = $item['PROPERTIES']['DESCRIPTION']['~VALUE']['TEXT'] ?>
        <div class="col col-3 contacts-item" id="<?= v::addEditingActions($item, $this) ?>">
            <div id="<?= $mapId($idx) ?>" class="contacts-map"></div>
            <div class="contacts-info">
                <p class="contacts-name"><?= $item['NAME'] ?></p>
                <? if (!v::isEmpty($description)): ?>
                    <? // TODO editable-area ?>
                    <p class="contacts-description"><?= $description ?></p>
                <? endif ?>
                <? // TODO editable-area ?>
                <p class="contacts-text">
                    <?= $item['PREVIEW_TEXT'] ?>
                </p>
            </div>
            <div class="contacts-bottom">
                <div class="grid">
                    <div class="col col-2">
                        <? if (!v::isEmpty($item['LAT_LONG'])): ?>
                            <?
                            // TODO better map
                            $point = $item['LAT_LONG']['long'].','.$item['LAT_LONG']['lat'];
                            $query = http_build_query([
                                'mode' => 'whatshere',
                                'whatshere[point]' => $point,
                                'll' => $point,
                                'zoom' => 14
                            ]);
                            ?>
                            <? $link = 'https://yandex.ru/maps/?'.$query ?>
                            <a href="<?= $link ?>" target="_blank" class="simple-btn"><span>Посмотреть на большой карте</span></a>
                        <? endif ?>
                    </div>
                    <? if (!v::isEmpty($directions)): ?>
                        <div class="col col-2">
                            <a href="<?= $directions ?>" target="_blank" class="simple-btn"><span>Скачать схему проезда</span></a>
                        </div>
                    <? endif ?>
                </div>
            </div>
        </div>
    <? endforeach ?>
</div>
