<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if (!empty($arResult["ORDER"]))
{
	$holidayText = "";
	$datetime2 = date_create(date("Y-m-d"));
	$res = CUser::GetList($o, $b, array("ID_EQUAL_EXACT" => 1), array("SELECT"=>array("UF_HOLYDAY", "UF_HOLYDAY_TO")));
	if ($ob = $res->Fetch()){
		if ($ob["UF_HOLYDAY"]){
			$holidayText = "Ваш заказ будет обработан <strong>".$ob["UF_HOLYDAY_TO"]."</strong><br><br>";
		}
	}
	
	?>
	<b><?=GetMessage("SOA_TEMPL_ORDER_COMPLETE")?></b><br /><br />
	<table class="sale_order_full_table">
		<tr>
			<td>
				<?=$holidayText?><?=GetMessage("SOA_TEMPL_ORDER_SUC", Array("#ORDER_DATE#" => $arResult["ORDER"]["DATE_INSERT"], "#ORDER_ID#" => $arResult["ORDER_ID"]))?><br /><br />
				<div style="font-size: 15px;">Уважаемый клиент! Менеджер проверит наличие товара и отправит на указанную Вами электронную почту информацию об оплате.</div>
			</td>
		</tr>
		<tr>
		    <td>
		        <div style="font-size: 15px;"><a href="/personal/order/detail/<?=$arResult["ORDER_ID"]?>/">Ссылка на заказ в личном кабинете</a></div>
		    </td>
		</tr>
	</table>
	<?
	if (!empty($arResult["PAY_SYSTEM"]))
	{
		?>
		<br /><br />

		<table class="sale_order_full_table">
			<tr>
				<td class="logo">
					<div class="pay_name"><?=GetMessage("SOA_TEMPL_PAY")?></div>
					<?=CFile::ShowImage($arResult["PAY_SYSTEM"]["LOGOTIP"], 100, 100, "border=0", "", false);?>
					<div class="paysystem_name"><?= $arResult["PAY_SYSTEM"]["NAME"] ?></div><br>
				</td>
			</tr>
		</table>
		<?
	}
	
   $db_vals = CSaleOrderPropsValue::GetList(
            array("SORT" => "ASC"),
            array(
                    "ORDER_ID" => $arResult["ORDER_ID"],
					"ORDER_PROPS_ID"=>6
    
                )
        );
     if($arVals = $db_vals->Fetch()){
	 
        if($arVals["VALUE"]==670){
			
$arFilter = Array("IBLOCK_ID"=>9, "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, Array());
$ob = $res->GetNextElement();
 $arFields = $ob->GetFields();  
	?>
	<div>
		<?=$arFields["PREVIEW_TEXT"]?>
	</div>
	<?
	

		}
   }
                  

}
else
{
	?>
	<b><?=GetMessage("SOA_TEMPL_ERROR_ORDER")?></b><br /><br />

	<table class="sale_order_full_table">
		<tr>
			<td>
				<?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST", Array("#ORDER_ID#" => $arResult["ORDER_ID"]))?>
				<?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST1")?>
			</td>
		</tr>
	</table>
	<?
}
?>
