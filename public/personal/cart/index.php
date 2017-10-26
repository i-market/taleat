<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Корзина товаров");

use App\Cart;
?>

<? (new Cart)->index() ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>