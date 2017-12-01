<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");
$APPLICATION->SetPageProperty('layout', 'bare');

use App\View as v;
use App\Layout;
use App\Components;
?>

<? Layout::showPersonalPageWrapper('header') ?>
<div class="tabs-inner">
    <div class="data-center">
        <? Components::showPersonalProfile() ?>
        <? Components::showNewsletterSub() ?>
    </div>
</div>
<? Layout::showPersonalPageWrapper('footer') ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
