<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

      <div><span>Моя корзина</span></div>
<?
if (IntVal($arResult["NUM_PRODUCTS"])>0)
{
?>

	
	<div><?=$arResult["NUM_PRODUCTS"]?> <?=fn_ReplaceForm($arResult["NUM_PRODUCTS"], "товар", "товара" , "товаров");?></div>
	<?
}
else
{
?>    <div>пока еще пуста</div>
		  
<?
}
?>