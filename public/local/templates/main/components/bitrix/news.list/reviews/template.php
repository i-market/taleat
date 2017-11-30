<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Core\Strings as str;
use App\View as v;
?>
<div class="wrap-reviews-item">
    <? foreach ($arResult['ITEMS'] as $item): ?>
        <div class="reviews-item">
            <p class="top">
                <span class="date"><?= $item['DISPLAY_ACTIVE_FROM'] ?></span>
                <? $order = $item['PROPERTIES']['ORDER_NUM']['VALUE'] ?>
                <? if (!v::isEmpty($order)): ?>
                    <span class="number"><?= (!str::contains(str::lower($order), 'заказ') ? 'Заказ ' : '').$order ?></span>
                <? endif ?>
            </p>
            <? $city = $item['PROPERTIES']['CITY']['VALUE'] ?>
            <p class="name"><?= $item['NAME'].(!v::isEmpty($city) ? " ({$city})" : '') ?></p>
            <p class="paragraph"><?= $item['PREVIEW_TEXT'] ?></p>
            <p class="link">
                <span class="reviews-item-link">читать полностью</span>
            </p>
        </div>
    <? endforeach ?>
</div>
<?= $arResult['NAV_STRING'] ?>>
