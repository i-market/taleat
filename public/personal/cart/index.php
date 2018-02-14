<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Корзина товаров");

use App\Cart;
?>

<? $cart = new Cart() ?>
<? $cart->index() ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>