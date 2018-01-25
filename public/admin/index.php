<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Панель администратора");

use App\App;
use App\Auth;
use App\Admin;

if (!Auth::hasAdminPanelAccess($USER)) {
    App::getInstance()->assert(false, "auth issue. shouldn't happen.");
    die('Недостаточно прав');
}
?>

<?= Admin::dispatch($_REQUEST) ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>