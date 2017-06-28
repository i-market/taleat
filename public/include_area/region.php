
<?/*
<?$arCity=Array();
$arFilter = Array("IBLOCK_ID"=>7, "ACTIVE"=>"Y", "!PROPERTY_CITY"=>false);
$res = CIBlockElement::GetList(Array(), $arFilter, array("PROPERTY_CITY"), false ,array());
while($ob = $res->GetNextElement()){ 
 $arFields = $ob->GetFields();  

$arCity[]=$arFields['PROPERTY_CITY_VALUE'];
 }

 ?>
 <br>
 <br>
 <form action="" method="get" id="change_city">
 <select name="cur" class="curr" onchange="changeprop(this)">
 <option value="none">Выбирите регион</option>
 <?
 
 $arFilter = Array("IBLOCK_ID"=>6, "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array('NAME'=>'ASC'), $arFilter, false, false ,array());
while($ob = $res->GetNextElement()){ 
 $arFields = $ob->GetFields();  

	?>
	<option value="<?=$arFields["ID"]?>"  <?if(($_REQUEST['cur'])>0):?><?if($_REQUEST['cur']==$arFields['ID']):?>selected<?endif?><?endif?> ><?=$arFields["NAME"]?></option>
	<?
	
 
 
 }
?>

</select>
</form>
	   <script type="text/javascript">
		function changeprop(){
	
			$('#change_city').submit();
		}
		  </script>
		  
		  
		  <br>
		  <br>
		  
	<?if(strlen(trim($_REQUEST["cur"]))>0):?>
	
		<?
		$arFilter = Array("IBLOCK_ID"=>7, "ACTIVE"=>"Y", "PROPERTY_CITY"=>$_REQUEST["cur"]);
		$res = CIBlockElement::GetList(Array(), $arFilter, false, false ,array());
		$count=$res->SelectedRowsCount();
		$i=0;
		while($ob = $res->GetNextElement()){ 
		$i++;
		 $arFields = $ob->GetFields();  
		 ?>
		 <div class="cite_s <?if($count!=$i):?>bord_cit<?endif?>" >
		 <div class="name_company">
			<?=$arFields["NAME"]?>
		 </div>
		 	 <div class="text_company">
			<?=$arFields["PREVIEW_TEXT"]?>
		 </div>
		 </div>
		 <?
		 
		 
		 }
		
		
		?>
	
	
	
	
	
	
	
	<?endif?>
*/?>