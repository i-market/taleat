<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Регистрация");
?> 
<script>
$(function(){
	$('#partner-phone').inputmask('+7 (999) 999-99-99', {
		'placeholder': "+7 (___) ___-__-__"
	});
});
</script>
<?$code=$GLOBALS["APPLICATION"]->CaptchaGetCode();
	$cptcha = new CCaptcha();

	global $USER;
	$arGroups = CUser::GetUserGroup($USER->GetID());
	
		if(in_array(9, $arGroups))
			{
			LocalRedirect('/partneram/');
			}
			else{
			if($USER->IsAuthorized()){
				$USER->Logout();
				LocalRedirect('/partneram/registration/');
				}
			}

?>



<?if($_REQUEST["reg"]=='Зарегистрироваться'):?>
<?

	$arError=Array();
			if(strlen(trim($_REQUEST["NAME"]))==0)
				{
					$arError[]='Вы не ввели контактное лицо!';
				}
			if(strlen(trim($_REQUEST["COMPANY"]))==0)
				{
					$arError[]='Вы не ввели компанию!';
				}
			if(strlen(trim($_REQUEST["CITY"]))==0)
				{
					$arError[]='Вы не ввели город!';
				}
			if(strlen(trim($_REQUEST["PHONE"]))==0)
				{
					$arError[]='Вы не ввели телефон!';
				}
				
			if(strlen(trim($_REQUEST["CITY"]))==0)
				{
					$arError[]='Вы не ввели логин!';
				}

            $arError = array_merge($arError, Forms::validateTermsAgreement($_REQUEST));

				if(!strlen($_REQUEST["captcha_word"])>0)
				{ 
				$arError[]='Не введен защитный код!';
				}
			elseif(!$cptcha->CheckCode($_REQUEST["captcha_word"],$_REQUEST["captcha_sid"])){ 
				$arError[]= "Код с картинки заполнен не правильно!";
				}
				
		if(count($arError))
		{	
			foreach($arError as $error){
				?>
				<div class="error"><?=$error?></div>
				<?
			
			}
			
			
		}else{
		
			global $USER;
		$arResult = $USER->Register($_REQUEST["LOGIN"], $_REQUEST["NAME"]."_prt", "", $_REQUEST["USER_PASSWORD"], $_REQUEST["USER_CONFIRM_PASSWORD"], $_REQUEST["EMAIL"]);
			if($arResult["TYPE"]=='ERROR')
				{
					?>
					<div class="error">
						<?=$arResult["MESSAGE"]?>
					</div>
					<?
				
				}
				else{
					$rsUsers = CUser::GetList(($by="personal_country"), ($order="desc"), Array("LOGIN"=>$_REQUEST["LOGIN"]));
					while($rsUsers->NavNext(true, "f_")) :
						$id=$f_ID;
					endwhile;
					$USER->Logout();
					$user = new CUser;
				$fields = Array(
				  "NAME"=>str_replace("_prt", "", $_REQUEST["NAME"]),
				  "LAST_NAME"=>$_REQUEST["LAST_NAME"],
				  "SECOND_NAME"=>$_REQUEST["SECOND_NAME"],
				  "WORK_COMPANY"=>$_REQUEST["COMPANY"],
				  "PERSONAL_CITY"=>$_REQUEST["CITY"],
				  "PERSONAL_PHONE"=>$_REQUEST["PHONE"],
				  "ACTIVE"            => "N",
				  "GROUP_ID"          => array(3,5,4,9),
				  );
				$user->Update($id, $fields);
				$arSendFields = array(

					"LINK"=>"/bitrix/admin/user_edit.php?lang=ru&ID=".$id
				);							
					$send=CEvent::Send("REG_PARTNER","s1", $arSendFields); 

					$holidayText = "";
					$datetime2 = date_create(date("Y-m-d"));
					$res = CUser::GetList($o, $b, array("ID_EQUAL_EXACT" => 1), array("SELECT"=>array("UF_HOLYDAY", "UF_HOLYDAY_TO")));
					if ($ob = $res->Fetch()){
						if ($ob["UF_HOLYDAY"]){
							$holidayText = "<br><br>Ваша заявка на регистрацию будет рассмотрена <strong>".$ob["UF_HOLYDAY_TO"]."</strong><br><br>";
						}
					}?>
					<div class="success">Спасибо! Доступ активируется поле одобрения администратором.<?=$holidayText?></div>
					<?
				
				}
		}
	?>
<?endif?>
<div <?if($id):?>style="display:none;"<?endif?>>
<form action="/partneram/registration/" method="POST">
		<table cellpadding="0" cellspacing="0" border="0" class="partners_reg_tab">	
			<tr>
			<td class="on_td">
				Фамилия* :
			</td>
			<td class="tw_td">
				<input type="text" name="LAST_NAME" value="<?=htmlspecialchars($_REQUEST["LAST_NAME"])?>" /> 
			</td>
			</tr>
			<tr>
			<td class="on_td">
				Имя* :
			</td>
			<td class="tw_td">
				<input type="text" name="NAME" value="<?=htmlspecialchars($_REQUEST["NAME"])?>" /> 
			</td>
			</tr>
			<tr>
			<td class="on_td">
				Отчество* :
			</td>
			<td class="tw_td">
				<input type="text" name="SECOND_NAME" value="<?=htmlspecialchars($_REQUEST["SECOND_NAME"])?>" /> 
			</td>
			</tr>
						<tr>
			<td class="on_td">
				Компания* :
			</td>
			<td class="tw_td">
				<input type="text" name="COMPANY" value="<?=htmlspecialchars($_REQUEST["COMPANY"])?>" /> 
			</td>
			</tr>
			
			<tr>
			<td class="on_td">
				Город* :
			</td>
			<td class="tw_td">
				<input type="text" name="CITY" value="<?=htmlspecialchars($_REQUEST["CITY"])?>" /> 
			</td>
			</tr>
						<tr>
			<td class="on_td">
				Телефон* :
			</td>
			<td class="tw_td">
				<input type="text" id="partner-phone" name="PHONE" value="<?=htmlspecialchars($_REQUEST["PHONE"])?>" /> 
			</td>
			</tr>
			
			<tr>
			<td class="on_td">
				Логин* :
			</td>
			<td class="tw_td">
				<input type="text" name="LOGIN" value="<?=htmlspecialchars($_REQUEST["LOGIN"])?>" /> 
			</td>
			</tr>
						<tr>
			<td class="on_td">
				Пароль* :
			</td>
			<td class="tw_td">
				<input type="password" name="USER_PASSWORD" value="" /> 
			</td>
			</tr>
			<tr>
			<td class="on_td">
				Подтверждение пароля* :
			</td>
			<td class="tw_td">
				<input type="password" name="USER_CONFIRM_PASSWORD" value="" /> 
			</td>
			</tr>
						<tr>
			<td class="on_td">
				E-mail* :
			</td>
			<td class="tw_td">
				<input type="text" name="EMAIL" value="<?=htmlspecialchars($_REQUEST["EMAIL"])?>" /> 
			</td>
			</tr>
            <tr>
                <td class="on_td"></td>
				<td>
					<? include $_SERVER['DOCUMENT_ROOT'].'/local/partials/forms/terms.php' ?>
				</td>
			</tr>
			<tr>
				<td class="on_td">
					Введите код  с картинки* : 
				</td>
				<td class="tw_td">
					 <input type="hidden" name="captcha_sid" value="<?=$code?>" /> 
			  <img src="/bitrix/tools/captcha.php?captcha_code=<?=$code?>" width="180" height="40" alt="CAPTCHA" /> 
			<br><input type="text" name="captcha_word" value="" class="captcha_inp" autocomplete="off"/>
	
				</td>
			</tr>
			
			
			
			<tr>
				<td>
				</td>
				
				<td>
					<input type="submit" name="reg" value="Зарегистрироваться" />
				</td>
				
			</tr>
			
		
		</table>
		
		
		</form>
	
		<br>
	<div>Информация:<br>
* Поля обязательные к заполнению

</div>

	</div>






 <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>