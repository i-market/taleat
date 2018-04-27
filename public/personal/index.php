<?
define('NEED_AUTH', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");
$APPLICATION->SetPageProperty('layout', 'personal');

use App\Components;
use App\Auth;
?>

<div class="tabs-inner">
    <div class="data-center">
        <? if (Auth::isPartner($USER) || Auth::isUnconfirmedPartner($USER)): ?>
            <? Components::showPartnerProfile() ?>
        <? else: ?>
            <? Components::showPersonalProfile() ?>
        <? endif ?>
        <? Components::showNewsletterSub() ?>
    </div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
