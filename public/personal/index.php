<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");
$APPLICATION->SetPageProperty('layout', 'personal');

use App\Components;
?>

<div class="tabs-inner">
    <div class="data-center">
        <? Components::showPersonalProfile() ?>
        <? Components::showNewsletterSub() ?>
    </div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
