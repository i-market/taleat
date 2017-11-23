<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новое техническое заключение");
$APPLICATION->SetPageProperty('layout', 'bare');
$APPLICATION->SetPageProperty('body_class', 'bg');

use App\Layout;
use App\View as v;
use App\Report;
?>

<div class="wrap">
    <? Layout::showBreadcrumbs() ?>
</div>
<section class="section-title">
    <div class="wrap">
        <div class="section-title-block">
            <h2>Техническое заключение на изделие Babyliss</h2>
        </div>
    </div>
</section>
<section class="technical-conclusion">
    <div class="wrap">
        <?= v::render('partials/partner/reports/form.php', Report::context($_REQUEST)) ?>
    </div>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>