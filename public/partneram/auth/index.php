<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Авторизация");
?>
<?
use App\View as v;

LocalRedirect(v::path('partneram'));
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>