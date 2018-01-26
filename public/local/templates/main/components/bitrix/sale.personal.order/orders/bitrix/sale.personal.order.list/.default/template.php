<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use App\View as v;
use Core\Underscore as _;
use App\OrderStatus;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

Asset::getInstance()->addJs("/bitrix/components/bitrix/sale.order.payment.change/templates/.default/script.js");
Asset::getInstance()->addCss("/bitrix/components/bitrix/sale.order.payment.change/templates/.default/style.css");
CJSCore::Init(array('clipboard', 'fx'));

Loc::loadMessages(__FILE__);

$isCompleted = function ($order) {
    return $order['ORDER']['CANCELED'] === 'Y'
        || $order['ORDER']['STATUS_ID'] === OrderStatus::COMPLETED;
};

$completed = iter\filter($isCompleted, $arResult['ORDERS']);
$active = iter\filter(_::negate($isCompleted), $arResult['ORDERS']);

$orderStatus = function ($order) use ($arResult) {
    if ($order['ORDER']['CANCELED'] === 'Y') {
        return [
            'text' => Loc::getMessage('SPOL_TPL_ORDER_CANCELED'),
            'state' => 'disabled'
        ];
    } elseif ($order['ORDER']['IS_ALLOW_PAY'] === 'N') {
        return [
            'text' => Loc::getMessage('SPOL_TPL_RESTRICTED_PAID'),
            'state' => 'ok'
        ];
    } else {
        $statusId = $order['ORDER']['STATUS_ID'];
        return [
            'text' => $arResult['INFO']['STATUS'][$statusId]['NAME'],
            'state' => in_array($statusId, [OrderStatus::OUT_OF_STOCK])
                ? 'issue'
                : 'ok'
        ];
    }
};
$orderTitle = function ($order) use ($arParams) {
    return join(' ', [
        Loc::getMessage('SPOL_TPL_ORDER'),
        Loc::getMessage('SPOL_TPL_NUMBER_SIGN'),
        htmlspecialcharsbx($order['ORDER']['ACCOUNT_NUMBER']),
        Loc::getMessage('SPOL_TPL_FROM_DATE'),
        $order['ORDER']['DATE_INSERT']->format($arParams['ACTIVE_DATE_FORMAT'])
    ]);
};
$formatPrice = function ($price, $item) {
    return CCurrencyLang::CurrencyFormat($price, $item['CURRENCY'], true);
};
$showOrder = function ($order, $class) use ($arResult, $orderStatus, $orderTitle, $formatPrice) {
    ?>
    <? $status = $orderStatus($order) ?>
    <div class="my-orders-item <?= $class ?> <?= $status['state'] === 'disabled' ? 'disabled' : '' ?>">
        <p class="top">
            <span class="top-name <?= $status['state'] === 'issue' ? 'red' : '' ?>"><?= $status['text'] ?></span>
        </p>
        <p class="name-info">
            <span class="name"><?= $orderTitle($order) ?></span>
            <span class="price"><?= $order['ORDER']['FORMATED_PRICE'] ?></span>
        </p>
        <div class="hidden-info">
            <table>
                <? foreach (iter\values($order['BASKET_ITEMS']) as $idx => $item): ?>
                    <tr>
                        <td><?= $idx + 1 ?></td>
                        <td><?= $item['NAME'] ?></td>
                        <td><?= $item['QUANTITY'].' x '.$formatPrice($item['PRICE'], $item) ?></td>
                        <td><?= $formatPrice($item['PRICE'] * $item['QUANTITY'], $item) ?></td>
                    </tr>
                <? endforeach ?>
            </table>
        </div>
        <? if ($order['ORDER']['CANCELED'] !== 'Y'): ?>
            <? foreach ($order['PAYMENT'] as $payment): ?>
                <? if ($payment['PAID'] !== 'Y' && $order['ORDER']['IS_ALLOW_PAY'] !== 'N'): ?>
                    <a target="_blank" href="<?=htmlspecialcharsbx($payment['PSA_ACTION_FILE'])?>" class="yellow-btn">
                        Оплатить <span><?= $payment['FORMATED_SUM'] ?></span>
                    </a>
                <? endif ?>
            <? endforeach ?>
        <? endif ?>
        <div class="more-info">
            <div class="more-info-block">
                <? if ($order['ORDER']['CANCELED'] !== 'Y'): ?>
                    <a href="<?= htmlspecialcharsbx($order['ORDER']['URL_TO_CANCEL']) ?>" class="delete"><?= Loc::getMessage('SPOL_TPL_CANCEL_ORDER') ?></a>
                <? else: ?>
                    <a href="<?=htmlspecialcharsbx($order["ORDER"]["URL_TO_COPY"])?>"><?=Loc::getMessage('SPOL_TPL_REPEAT_ORDER')?></a>
                <? endif ?>
            </div>
            <div class="more-info-block">
                <span class="more">Подробнее</span>
            </div>
        </div>
    </div>
    <?
};
?>
<? // TODO style errors ?>
<?
if (!empty($arResult['ERRORS']['FATAL']))
{
    foreach($arResult['ERRORS']['FATAL'] as $error)
    {
        ShowError($error);
    }
    $component = $this->__component;
    if ($arParams['AUTH_FORM_IN_TEMPLATE'] && isset($arResult['ERRORS']['FATAL'][$component::E_NOT_AUTHORIZED]))
    {
        $APPLICATION->AuthForm('', false, false, 'N', false);
    }

}
else
{
    if (!empty($arResult['ERRORS']['NONFATAL'])) {
        foreach ($arResult['ERRORS']['NONFATAL'] as $error) {
            ShowError($error);
        }
    }
}
?>
<div class="my-orders">
    <? if (v::isEmpty($arResult['ORDERS'])): ?>
        <div class="empty-state-page">
            <div class="editable-area default-page">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => v::includedArea('personal/order/no_orders.php')
                    )
                ); ?>
            </div>
        </div>
    <? else: ?>
        <div class="grid">
            <? if (!iter\isEmpty($active)): ?>
                <div class="col col-2">
                    <p class="title">Активные заказы</p>
                    <div class="wrap-active-orders">
                        <? foreach ($active as $order): ?>
                            <? $showOrder($order, 'active-orders') ?>
                        <? endforeach ?>
                    </div>
                </div>
            <? endif ?>
            <? if (!iter\isEmpty($completed)): ?>
                <div class="col col-2">
                    <p class="title">Выполненные заказы</p>
                    <div class="wrap-completed-orders">
                        <? foreach ($completed as $order): ?>
                            <? $showOrder($order, 'completed-orders') ?>
                        <? endforeach ?>
                    </div>
                </div>
            <? endif ?>
        </div>
    <? endif ?>
</div>