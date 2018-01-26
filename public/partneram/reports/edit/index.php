<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Редактирование");
$APPLICATION->SetPageProperty('layout', 'bare');
$APPLICATION->SetPageProperty('body_class', 'bg');

use App\Layout;
use App\View as v;
use App\Report;
use Core\Session;

if (!isset($_REQUEST['id'])) {
    LocalRedirect('/404.php');
}
$result = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = Report::update($_REQUEST);
    if ($result['success']) {
        Session::addFlash($result['message']);
        LocalRedirect(v::path('partneram/reports'));
    }
}
$element = Report::element($_REQUEST['id']);
$context = Report::context(Report::elementFields($element), $_REQUEST, $result, [
    'mode' => 'edit',
    'element' => $element
]);
?>

<div class="wrap">
    <? Layout::showBreadcrumbs() ?>
</div>
<? if (Report::isEditingDisallowed($element['PROPERTY_STATUS_ENUM_ID'], $element['PROPERTY_USER_VALUE'])): ?>
    <div class="wrap">
        <div class="empty-state-page">
            <div class="h3">Эту заявку невозможно отредактировать</div>
        </div>
    </div>
<? else: ?>
    <section class="section-title">
        <div class="wrap">
            <div class="section-title-block">
                <h2>Техническое заключение на изделие Babyliss <?= '№'.$element['PROPERTY_NUMER_VALUE'] ?></h2>
            </div>
        </div>
    </section>
    <section class="technical-conclusion">
        <div class="wrap">
            <?= v::render('partials/partner/reports/form.php', $context) ?>
        </div>
    </section>
<? endif ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>