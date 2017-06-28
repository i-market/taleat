<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Письмо | TALEAT");
$APPLICATION->SetTitle("Письмо");
?> 
<?$code=$GLOBALS["APPLICATION"]->CaptchaGetCode();
	$cptcha = new CCaptcha();?>

<?if($_REQUEST["LETTER"]=="Отправить"){
	$arError=Array();
	if(strlen(trim($_REQUEST["NAME"]))==0){
		$arError[]="Не заполнено контактное лицо!";
	}

	if(strlen(trim($_REQUEST["EMAIL"]))==0){
		$arError[]="Не заполнен E-mail!";
	}elseif(!filter_var($_REQUEST["EMAIL"], FILTER_VALIDATE_EMAIL))
	{
		$arError[]="Некорректный E-mail!";
	}
	if (!isset($_REQUEST['AGREED_PERSONAL_DATA'])) {
		$arError[]="Согласие на обработку персональных данных обязательно!";
	}
	if(!strlen($_REQUEST["captcha_word"])>0)
	{
		$arError[]='Не введен защитный код!';
	}
	elseif(!$cptcha->CheckCode($_REQUEST["captcha_word"],$_REQUEST["captcha_sid"])){
		$arError[]= "Код с картинки заполнен не правильно!";
	}
	if(count($arError)>0)
	{
		foreach($arError as $error){
			?><div class="error"><?=$error?></div><?

		}

	}
	else{

		$arSendFields = array(
			"NAME"=>$_REQUEST["NAME"],
			"PHONE"=>$_REQUEST["PHONE"],
			"EMAIL"=>$_REQUEST["EMAIL"],
			"TEXT"=>$_REQUEST["TEXT"],
		);
		$send=CEvent::Send("LETTER","s1", $arSendFields);

		if($send)
		{
			?><div class="succes">Спасибо! Ваш письмо успешно отправлено!</div><?
		}


	}


}?>
<div <?if($send):?>style="display:none;"<?endif?>>
<form action="/letter/" method="POST" >
	<? $isInitial = $_SERVER['REQUEST_METHOD'] === 'GET' ?>
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="on_td">
				Контактное лицо:
			</td>
			<td class="tw_td">
				<input type="text" name="NAME" value="<?=htmlspecialchars($_REQUEST["NAME"])?>"> 
			</td>
		</tr>
		<tr>
			<td class="on_td">
				Телефон:
			</td>
			<td class="tw_td">
				<input type="text" name="PHONE" value="<?=htmlspecialchars($_REQUEST["PHONE"])?>"> 
			</td>
		</tr>	
		<tr>
			<td class="on_td">
				E-mail:
			</td>
			<td class="tw_td">
				<input type="text" name="EMAIL" value="<?=htmlspecialchars($_REQUEST["EMAIL"])?>"> 
			</td>
			
			
		</tr>	
		<tr>
			<td colspan="2"> 
				Текст сообщения:<br>
				<textarea name="TEXT" class="textr"><?=htmlspecialchars($_REQUEST["TEXT"])?></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<? $key = 'AGREED_PERSONAL_DATA' ?>
				<input id="personal-data" name="<?= $key ?>" type="checkbox"<?= isset($_REQUEST[$key]) || $isInitial ? ' checked' : '' ?>>
				<label for="personal-data">
					Я соглашаюсь на <a href="<?= SITE_DIR.'terms/personal-data/' ?>" target="_blank">обработку персональных данных</a>
				</label>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<? $key = 'AGREED_ADS' ?>
				<input id="ads" name="<?= $key ?>" type="checkbox"<?= isset($_REQUEST[$key]) || $isInitial ? ' checked' : '' ?>>
				<label for="ads">
					Я соглашаюсь на <a href="<?= SITE_DIR.'terms/ads/' ?>" target="_blank">получение рекламы</a>
				</label>
			</td>
		</tr>
			<tr>	<td class="on_td">
				Введите код:
			</td>
			<td class="tw_td">
			  <input type="hidden" name="captcha_sid" value="<?=$code?>" /> 
			  <img src="/bitrix/tools/captcha.php?captcha_code=<?=$code?>" width="180" height="40" alt="CAPTCHA" /> 
			<br><input type="text" name="captcha_word" value="" class="captcha_inp" autocomplete="off"/>
	
			
			</td>
			</tr>
	</table>
	<input type="submit" name="LETTER" value="Отправить" />
</form>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>