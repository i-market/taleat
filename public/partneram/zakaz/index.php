<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оформить заказ");
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/templates/main_page/include/phpexcel/PHPExcel.php');
?> 




<?

$arOrder=Array();
if($_REQUEST['order']=='Оформить заказ')
{	$arOrder=Array();
	$arError=Array();
	$i=0;
	while($i<count($_REQUEST['NOMER'])){	

		if((strlen(trim($_REQUEST['MODEL'][$i])))||(strlen(trim($_REQUEST['NOMER'][$i])))||(strlen(trim($_REQUEST['NAME'][$i])))||(strlen(trim($_REQUEST['COUNT'][$i])))){
	
			$arOrder[$i]['MODEL']=$_REQUEST['MODEL'][$i];
			$arOrder[$i]['NOMER']=$_REQUEST['NOMER'][$i];
			$arOrder[$i]['NAME']=$_REQUEST['NAME'][$i];
			$arOrder[$i]['COUNT']=$_REQUEST['COUNT'][$i];
		}
		
		$i++;
	
	}
			foreach($arOrder as $r=>$val){
				if(strlen(trim($val['NOMER']))==0)
					{
						$arError['NOMER'][$r]=1;
					}
				if(strlen(trim($val['NAME']))==0)
					{
						$arError['NAME'][$r]=1;
					}
				if(strlen(trim($val['COUNT']))==0)
					{
						$arError['COUNT'][$r]=1;
					}					
			}
			
			

	
	if(count($arError)==0){
		global $USER;
		$path1="/tmp/".$USER->GetID()."/".mktime()."/";
		$path=$_SERVER["DOCUMENT_ROOT"].$path1;
	
		CheckDirPath($path);
	
	
	


require_once $_SERVER["DOCUMENT_ROOT"].'/bitrix/templates/main_page/include/phpexcel/PHPExcel/IOFactory.php';
global $USER;
$rsUser = CUser::GetByID($USER->GetID());

$arUser = $rsUser->Fetch();

$book = PHPExcel_IOFactory::load("zakaz.xls");    //открываем файл
$book->getActiveSheet()->setCellValue('D3', date("d.m.Y")); 
$book->getActiveSheet()->setCellValue('D5', $arUser['WORK_COMPANY']); 
$book->getActiveSheet()->setCellValue('D6', $arUser['PERSONAL_CITY']); 
$book->getActiveSheet()->setCellValue('D7', $arUser['PERSONAL_PHONE']); 



foreach($arOrder as $y=>$val){
	$t=$y+10;

	$book->getActiveSheet()->setCellValue('A'.$t, $y+1);     //изменяем нужные данные
	$book->getActiveSheet()->setCellValue('B'.$t, $val["MODEL"]);     //изменяем нужные данные
	
	$book->getActiveSheet()->setCellValue('C'.$t, $val["NOMER"]);     //изменяем нужные данные
	$book->getActiveSheet()->setCellValue('D'.$t, $val["NAME"]);     //изменяем нужные данные
	$book->getActiveSheet()->setCellValue('E'.$t, $val["COUNT"]);     //изменяем нужные данные
}




$objWriter = PHPExcel_IOFactory::createWriter($book, 'Excel5'); 
$objWriter->save($path."NovayaFormaZakazaBraun.xls");    // сохраняем изменнённый документ
/*global $USER;
$rsUser = CUser::GetByID($USER->GetID());

$arUser = $rsUser->Fetch();





$arSendFields = array(
					"COMPANY"	=> $arUser['WORK_COMPANY'],
					"CITY"	=> $arUser['PERSONAL_CITY'],  
					"PHONE"	=> $arUser['PERSONAL_PHONE'],
					"EMAIL"	=> $arUser['EMAIL'],
					"LINK"	=> '<a href="http://'.$_SERVER["HTTP_HOST"].'/'.$path1.'НоваяПрооформаЗаказаБраун.xls'.'">Ссылка</a>',
					"SERVER"=>$_SERVER["HTTP_HOST"]
					);
											
				$send=CEvent::Send('ZAKAZ_XLS',"s1", $arSendFields); 
				if($send>0)
					{
						?>
						<div class="success">Спасибо! Ваша заявка успешно отправлена!</div>
						<?
					}*/
				
					
					$thm = 'Заполнена заявка на сайте http://taleat.ru/';
					$msg = '';
					$mail_to = 'zakaz@taleat.ru';
					
				//echo send_mail($mail_to, $thm, $msg, $_SERVER["DOCUMENT_ROOT"].$path1.'НоваяПрооформаЗаказаБраун.xls', 'НоваяПрооформаЗаказаБраун.xls');
				echo send_mail($mail_to, "=?utf-8?B?".base64_encode($thm)."?=", $msg, $_SERVER["DOCUMENT_ROOT"].$path1.'NovayaFormaZakazaBraun.xls', 'NovayaFormaZakazaBraun.xls');
					
					
					
					
					

}
}
?>

<?if(count($arOrder)==0):?>
	<?if($_REQUEST['order']=='Оформить заказ'):?>
		<div class="error">Заполните поля</div>
	<?endif?>
<div class="ord_z_bord">
	<div class="mod1">Модель издедия</div>
	<div class="mod2">*Партномер (артикул)</div>
	<div class="mod3">*Наименование детали</div>
	<div class="mod4">*Количество</div>
	<div class="clear"></div>
</div>
<form action='' method='post'>
	<div class="block_ord">
		<div class="block_item" id="bs_0">
			<input type="text" name="MODEL[]" value="" />
			<input type="text" name="NOMER[]" value="" />
			<input type="text" name="NAME[]" value="" />
			<input type="text" name="COUNT[]" value="" />
		</div>
			<div class="block_item" id="bs_1">
			<input type="text" name="MODEL[]" value="" />
			<input type="text" name="NOMER[]" value="" />
			<input type="text" name="NAME[]" value="" />
			<input type="text" name="COUNT[]" value="" />
		</div>
				<div class="block_item" id="bs_2">
			<input type="text" name="MODEL[]" value="" />
			<input type="text" name="NOMER[]" value="" />
			<input type="text" name="NAME[]" value="" />
			<input type="text" name="COUNT[]" value="" />
		</div>
				<div class="block_item" id="bs_3">
			<input type="text" name="MODEL[]" value="" />
			<input type="text" name="NOMER[]" value="" />
			<input type="text" name="NAME[]" value="" />
			<input type="text" name="COUNT[]" value="" />
		</div>
	</div>
	<a href="javascript:void(0)" class="add_tovar">+ Добавить товар</a>
	<div class="bl_sub"><input type="submit" name="order" value="Оформить заказ" /></div>
	<div class="clear"></div>
</form>
<input type="hidden" class="bc_count" name="count" value="3" />
<script type="text/javascript">
	$(document).ready(function(){
	
	  $('.add_tovar').bind('click',function(){
			var count=$('.bc_count').val()*1+1;
			$('.block_ord').append('<div class="block_item" id="bs_'+count+'"><input type="text" name="MODEL[]" value="" />&nbsp;<input type="text" name="NOMER[]" value="" />&nbsp;<input type="text" name="NAME[]" value="" />&nbsp;<input type="text" name="COUNT[]" value="" /></div>');
			$('.bc_count').val(count);

	});
  
	
	});
</script>



<?elseif(count($arError)>0):?>


<div class="ord_z_bord">
	<div class="mod1">Модель издедия</div>
	<div class="mod2">*Партномер (артикул)</div>
	<div class="mod3">*Наименование детали</div>
	<div class="mod4">*Количество</div>
	<div class="clear"></div>
</div>

<form action='' method='post'>
<div class="block_ord">
<?$z=0?>
<?foreach($arOrder as $v=>$val):?>
	<div class="block_item" id="bs_<?=$z?>">
			<input type="text" name="MODEL[]" value="<?=$val["MODEL"]?>"  />
			<input type="text" name="NOMER[]" value="<?=$val["NOMER"]?>" <?if(strlen(trim($val["NOMER"]))==0):?>class="red_error"<?endif?> />
			<input type="text" name="NAME[]" value="<?=$val["NAME"]?>" <?if(strlen(trim($val["NAME"]))==0):?>class="red_error"<?endif?> />
			<input type="text" name="COUNT[]" value="<?=$val["COUNT"]?>" <?if(strlen(trim($val["COUNT"]))==0):?>class="red_error"<?endif?>/>
	</div>
	<?$z++;?>
<?endforeach?>
<?if($z<4):?>
	<?
	while($z<4)
		{
		?>
		<div class="block_item" id="bs_<?=$z-1?>">
			<input type="text" name="MODEL[]" value=""  />
			<input type="text" name="NOMER[]" value=""  />
			<input type="text" name="NAME[]" value=""  />
			<input type="text" name="COUNT[]" value="" />
	</div>
		
		
		<?
		
		$z++;
		}
	?>
	
<?endif?>

</div>
<a href="javascript:void(0)" class="add_tovar">+ Добавить товар</a>
	<div class="bl_sub"><input type="submit" name="order" value="Оформить заказ" /></div>
	<div class="clear"></div>
</form>

<input type="hidden" class="bc_count" name="count" value="<?=$z-1?>" />
<script type="text/javascript">
	$(document).ready(function(){
	
	  $('.add_tovar').bind('click',function(){
			var count=$('.bc_count').val()*1+1;
			$('.block_ord').append('<div class="block_item" id="bs_'+count+'"><input type="text" name="MODEL[]" value="" />&nbsp;<input type="text" name="NOMER[]" value="" />&nbsp;<input type="text" name="NAME[]" value="" />&nbsp;<input type="text" name="COUNT[]" value="" /></div>');
			$('.bc_count').val(count);

	});
	
		$('.red_error').each(function(){
			$(this).focus(function(){
				$(this).removeClass('red_error');
			
			});
		
			$(this).blur(function(){
				if($(this).val().length==0)
					{
						$(this).addClass('red_error');
					}
			
			});
		});
  
	
	});
</script>
<?endif?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>