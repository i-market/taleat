<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Мы предлагаем широкий ассортимент аксессуаров  к бытовой технике  фирмы Braun (Браун)");
$APPLICATION->SetPageProperty("keywords", "Сетки к бритвам, зубные насадки к электрическим зубным щеткам, запасные части к Браун, Braun, аксессуары к бытовой технике");
$APPLICATION->SetTitle("Отзывы");
?>


<h3 style="color: #0000ff; font-family: 'Times New Roman', Times; font-size: 14pt;">Оставьте, пожалуйста, отзыв. Это позволяет нам работать лучше для Вас!</h3>
<?if($_REQUEST["ADD"]=="Y"):?>
	<?$code=$GLOBALS["APPLICATION"]->CaptchaGetCode();
	$cptcha = new CCaptcha();
	?>
	<?
	if($_REQUEST["otziv"]=='Добавить')
	{
		$arError=Array();
		if(strlen(trim($_REQUEST["NAME"]))==0)
		{
			$arError[]='Вы не ввели свое имя!';
		}
		if(strlen(trim($_REQUEST["TEXT"]))==0)
		{
			$arError[]='Вы не ввели текст для отзыва!';
		}
		$arError = array_merge($arError, Forms::validateTermsAgreement($_REQUEST));
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
					?>
					<div class="error"><?=$error?></div>
					<?			
				}
			}
		else{
			$el = new CIBlockElement;
			$PROP = array();
			$PROP["CITY"] = $_REQUEST["CITY"];
			$PROP["ORDER_NUM"] = $_REQUEST["ORDER_NUM"];
			$arLoadProductArray = Array(
				"IBLOCK_ID"      => 5,
				"NAME"=> $_REQUEST["NAME"],
				"ACTIVE"=> "N",
				"PREVIEW_TEXT" => $_REQUEST["TEXT"],
				"PROPERTY_VALUES"=> $PROP,
				"DATE_ACTIVE_FROM"=>date('d.m.Y')
			);
			if($PRODUCT_ID = $el->Add($arLoadProductArray))
			{
			$arSendFields = array(
				"LINK"=>"/bitrix/admin/iblock_element_edit.php?WF=Y&ID=".$PRODUCT_ID."&type=otzivi&lang=ru&IBLOCK_ID=5&find_section_section=0"
				);							
											$send=CEvent::Send("ADD_OTZIV","s1", $arSendFields); 
					
			
			
			?><div class="succes">Спасибо! Ваш отзыв появится после проверки модератором!</div><?
			}

			
		
		}
		
		}
		
	?>
	<?$APPLICATION->SetTitle('Добавить отзыв');?>
	<div class="add_otz" <?if($PRODUCT_ID>0):?>style="display:none;"<?endif?>>
		<form action="/otzivi/?ADD=Y" method="POST">
		<table cellpadding="0" cellspacing="0" border="0">	
			<tr>
			<td class="on_td">
				Имя:
			</td>
			<td class="tw_td">
				<input type="text" name="NAME" value="<?=htmlspecialchars($_REQUEST["NAME"])?>" /> 
			</td>
			</tr>
			<tr>
			<td class="on_td">
				Номер заказа:
			</td>
			<td class="tw_td">
				<input type="text" name="ORDER_NUM" value="<?=htmlspecialchars($_REQUEST["ORDER_NUM"])?>" /> 
			</td>
			</tr>
			<tr>
			<td class="on_td">
				Город:
			</td>
			<td class="tw_td">
				<input type="text" name="CITY" value="<?=htmlspecialchars($_REQUEST["CITY"])?>" /> 
			</td>
			</tr>
			<tr>	
			<td class="on_td">
				Текст отзыва:
			</td>
			<td class="tw_td">
				<textarea name="TEXT"><?=htmlspecialchars($_REQUEST["TEXT"])?></textarea>
			</td>
			</tr>
            <tr>
				<td class="on_td"></td>
				<td>
					<? include $_SERVER['DOCUMENT_ROOT'].'/local/partials/forms/terms.php' ?>
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
		
		<input type="submit" name="otziv" value="Добавить" />
		</form>

	</div>
	
	<?if($PRODUCT_ID>0):?>
		<a href="/otzivi/">К списку отзывов</a>
	<?endif?>
<?else:?>



 <?$APPLICATION->IncludeComponent("bitrix:news.list", "otzivi", array(
	"IBLOCK_TYPE" => "otzivi",
	"IBLOCK_ID" => "5",
	"NEWS_COUNT" => "5",
	"SORT_BY1" => "ACTIVE_FROM",
	"SORT_ORDER1" => "DESC",
	"SORT_BY2" => "SORT",
	"SORT_ORDER2" => "ASC",
	"FILTER_NAME" => "",
	"FIELD_CODE" => array(
		0 => "DETAIL_PICTURE",
		1 => "",
	),
	"PROPERTY_CODE" => array(
		0 => "",
		1 => "LINK",
		2 => "space",
		3 => "",
	),
	"CHECK_DATES" => "N",
	"DETAIL_URL" => "",
	"AJAX_MODE" => "N",
	"AJAX_OPTION_JUMP" => "N",
	"AJAX_OPTION_STYLE" => "N",
	"AJAX_OPTION_HISTORY" => "N",
	"CACHE_TYPE" => "N",
	"CACHE_TIME" => "3600",
	"CACHE_FILTER" => "N",
	"CACHE_GROUPS" => "Y",
	"PREVIEW_TRUNCATE_LEN" => "",
	"ACTIVE_DATE_FORMAT" => "d.m.Y",
	"SET_TITLE" => "N",
	"SET_STATUS_404" => "N",
	"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
	"ADD_SECTIONS_CHAIN" => "N",
	"HIDE_LINK_WHEN_NO_DETAIL" => "N",
	"PARENT_SECTION" => "",
	"PARENT_SECTION_CODE" => "",
	"DISPLAY_TOP_PAGER" => "N",
	"DISPLAY_BOTTOM_PAGER" => "Y",
	"PAGER_TITLE" => "",
	"PAGER_SHOW_ALWAYS" => "N",
	"PAGER_TEMPLATE" => "",
	"PAGER_DESC_NUMBERING" => "N",
	"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
	"PAGER_SHOW_ALL" => "N",
	"DISPLAY_DATE" => "Y",
	"DISPLAY_NAME" => "Y",
	"DISPLAY_PICTURE" => "Y",
	"DISPLAY_PREVIEW_TEXT" => "Y",
	"AJAX_OPTION_ADDITIONAL" => ""
	),
	false
);?>



<?endif?>





<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>