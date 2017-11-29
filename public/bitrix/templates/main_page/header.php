<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
	global $USER;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">  

<head>

<title><?$APPLICATION->ShowTitle()?></title>
<?$APPLICATION->ShowHead()?>
<link rel="stylesheet" type="text/css" href="/bitrix/templates/main_page/fancybox/jquery.fancybox.css" media="screen" />
<?if ($APPLICATION->GetCurDir()=="/personal/order/bill/"){?>
	<link rel="stylesheet" type="text/css" href="/bitrix/templates/main_page/css/print.css" media="print"> 
<?}?>
<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/jcarousel.css" media="screen">
<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/jquery-1.7.2.min.js" ></script>
<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/jquery.treeview.js" ></script>
<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/jquery.cookie.js" ></script>
<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/jquery.jcarousel.js" ></script>
<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/jquery.jcarousel.js" ></script>
<script type="text/javascript" src="/bitrix/templates/main_page/fancybox/jquery.fancybox.js"></script>
<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/inputmask.js" ></script>
<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/jquery.inputmask.js" ></script>
<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/start.js" ></script>
<script type="text/javascript">
	$(document).ready(function(){

		$('.fancybox').fancybox({'titlePosition': 'over'});
	
	});

</script>
</head>
<body>
<?$APPLICATION->ShowPanel();?>
<?
if(($APPLICATION->GetCurDir()=="/partneram/")||($APPLICATION->GetCurDir()=="/partneram/ostatki/")||($APPLICATION->GetCurDir()=="/partneram/zakaz/")){
global $USER;
if(!$USER->IsAdmin()){

	$arGroups = CUser::GetUserGroup($USER->GetID());
	
		if(!in_array(9, $arGroups))
			{
				LocalRedirect('/partneram/auth/');
			}
	}
}
?>

<div class="headerBg">
	<div class="headerBg_block">
		<div class="wrapTop">
		<div></div></div></div></div>
		
<article id="wrapper">
  <section class="wrap">
  
   <header>
      <section>
        <a href="/" id="logotype"><img src="<?=SITE_TEMPLATE_PATH?>/images/logotype.png" alt="" title="Авторизированный сервисный центр Талеат-Сервис"></a>

        <aside class="phones">
          <div class="upper white"><span>Звоните: (495)</span></div>
          <div><img src="<?=SITE_TEMPLATE_PATH?>/images/phone.png" alt=""> <i>
		  	<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default", array(
							"AREA_FILE_SHOW" => "file",
							"PATH" => "/include_area/phone_1.php",
							"EDIT_TEMPLATE" => "standard.php"
							),
							false
							);
			?>
		  </i></div>
		  <?/*
          <div><img src="<?=SITE_TEMPLATE_PATH?>/images/phone.png" alt=""> <i>
		  	<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default", array(
							"AREA_FILE_SHOW" => "file",
							"PATH" => "/include_area/phone_2.php",
							"EDIT_TEMPLATE" => "standard.php"
							),
							false
							);
			?>
		  </i></div>
		  */?>
          <!--<div class="tcenter"><span class="normal" style="m">(доб. 427)</span></div>-->
        </aside>

        <aside class="barIcons">
          <span><a href="/"><img src="<?=SITE_TEMPLATE_PATH?>/images/home.gif" alt=""></a></span>
          <span><a href="/letter/"><img src="<?=SITE_TEMPLATE_PATH?>/images/email.gif" alt=""></a></span>
          <span><a href="/map.php"><img src="<?=SITE_TEMPLATE_PATH?>/images/map.gif" alt=""></a></span>
        </aside>

		<div class="login-wrapper">
			<?if(!$USER->IsAuthorized()){?><a class="login-link" id="goin" href="#reg-wrapper-fancy">Войти</a>
			<br>
			<a class="login-link" href="/login/?register=yes&backurl=%2Flogin%2F">Зарегистрироваться</a>
			<?} else {?>
				<div class="lk-info"><a href="/personal/?backurl=%2F">Личный кабинет [<?=$USER->GetLogin()?>]</a></div>
				<a href="/?logout=yes">Выйти</a>
			<?}?>
		</div>	
        <a class="basket" href="/personal/cart/" id="mini-cart">
            <?$APPLICATION->IncludeComponent(
	"bitrix:sale.basket.basket.line", 
	"basket", 
	array(
		"PATH_TO_BASKET" => SITE_DIR."personal/cart/",
		"PATH_TO_PERSONAL" => SITE_DIR."personal/",
		"SHOW_PERSONAL_LINK" => "N",
		"COMPONENT_TEMPLATE" => "basket",
		"PATH_TO_ORDER" => SITE_DIR."personal/order/make/",
		"SHOW_NUM_PRODUCTS" => "Y",
		"SHOW_TOTAL_PRICE" => "Y",
		"SHOW_EMPTY_VALUES" => "Y",
		"SHOW_AUTHOR" => "N",
		"PATH_TO_REGISTER" => SITE_DIR."login/",
		"PATH_TO_PROFILE" => SITE_DIR."personal/",
		"SHOW_PRODUCTS" => "N",
		"POSITION_FIXED" => "N",
		"HIDE_ON_BASKET_PAGES" => "N"
	),
	false
);?>
        </a>

        <form class="search" action="/search/index.php">
          <fieldset>
            <input type="text" name="q" />
            <input type="submit" name="s"  value="" />
          </fieldset>
        </form>
      </section>
    </header><!-- /header -->
	<article id="container">
	
	<section class="content">
	<?if($APPLICATION->GetcurDir()!="/"):?>
	<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "nav", array(
	"START_FROM" => "0",
	"PATH" => "",
	"SITE_ID" => "s1"
	),
	false
);?>
	<?endif?>
	
	<div class="sideleft">
          <div class="bordBlock_title">
            <a href="/catalog/">Каталог товаров</a>
          </div><!-- /.bordBlock_title -->

          <div class="bordBlock">
			
		<?$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "catalog", Array(
        	"IBLOCK_TYPE" => "catalog",	// Тип инфоблока
        	"IBLOCK_ID" => "3",	// Инфоблок
        	"SECTION_ID" => "",	// ID раздела
        	"SECTION_CODE" => "",	// Код раздела
        	"COUNT_ELEMENTS" => "N",	// Показывать количество элементов в разделе
        	"TOP_DEPTH" => "2",	// Максимальная отображаемая глубина разделов
        	"SECTION_FIELDS" => array(	// Поля разделов
        		0 => "",
        		1 => "",
        	),
        	"SECTION_USER_FIELDS" => array(	// Свойства разделов
        		0 => "",
        		1 => "",
        	),
        	"SECTION_URL" => "",	// URL, ведущий на страницу с содержимым раздела
        	"CACHE_TYPE" => "N",	// Тип кеширования
        	"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
        	"CACHE_GROUPS" => "Y",	// Учитывать права доступа
        	"ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
        	),
        	false
        );?>
  
          </div><!-- /.bordBlock -->

<?if($APPLICATION->GetCurPage() == SITE_DIR):?>
    <div class="serts">
        <h3>Сертификаты</h3>
        <a href="/images/braun.jpg" download target="_blank">
            <img src="/images/braun_mini.jpg" alt="Сертификат Braun" title="Скачать сертификат Braun" />
        </a>
        
        <a href="/images/babyliss.jpg" download target="_blank">
            <img src="/images/babyliss_mini.jpg" alt="Сертификат Babyliss" title="Скачать сертификат Babyliss" />
        </a>
        
        <a href="/images/delonghi.jpg" download target="_blank">
            <img src="/images/delonghi_mini.jpg" alt="Сертификат Delonghi" title="Скачать сертификат Delonghi" />
        </a>
    </div>
<?endif?>
		  <div id="reg-wrapper">
			<div id="reg-wrapper-fancy">
				<div class="bordBlock_title">
				<a href="<?if($USER->IsAuthorized()){?>/personal/<?}else{?>#<?}?>">Личный кабинет</a>
				</div><!-- /.bordBlock_title -->
				<div class="bordBlock reg" id="auth_block">
				<?


				if($_REQUEST["auth"]=="Войти"){

					$arAuthResult = $USER->Login($_REQUEST["login"], $_REQUEST["password"], "Y");
					$APPLICATION->arAuthResult = $arAuthResult;
					if($arAuthResult==1)
					{
						$arGroups = CUser::GetUserGroup($USER->GetID());
						$isPart = false;
						foreach ($arGroups as $gr){
							if ($gr==9) $isPart = true;
						}
						if (!$isPart) $APPLICATION-LocalRedirect($APPLICATION->GetCurDir());
						else $APPLICATION-LocalRedirect('/partneram/');
					}
				}
				?>
				<?if($USER->IsAuthorized()):?>
				<div class="auth_block"><a href="/personal/?backurl=%2F"><?=$USER->GetLogin()?></a>
				<br><br>
				<a href="/?logout=yes">Выйти</a>
				</div>
				<?else:?>

				<form class="contForm" action="" method="post">
				  <fieldset>
					<?if(is_array($arAuthResult) && !$_POST["AUTH_FORM_PARTNER"]){?>
						<script>
							$(document).ready(function(){
								$.fancybox({href:"#reg-wrapper-fancy",padding:25,margin:0});
							});
						</script>
						<div class="error"><?=$arAuthResult["MESSAGE"];?></div>
					<?}?>
					<div>
					  <label>Логин</label>
					  <input type="text" name="login" value="" />
					</div>

					<div>
					  <label>Пароль</label>
					  <input type="password" name="password" value="" />
					</div>

					<div class="tcenter" style="min-height: 0px;">
					  <div class="btnBlock"><input type="submit" value="Войти" name="auth"></div>
					</div>

					<div class="normal nomarg" style="min-height: 0px;">
					  <div class="left"><a href="/login/?forgot_password=yes&backurl=%2Flogin%2F">Забыли пароль?</a></div>
					  <div class="right"><a href="/login/?register=yes&backurl=%2Flogin%2F">Зарегистрироваться</a></div>
					</div>
				  </fieldset>
				</form>

				<?endif?>
				</div><!-- /.bordBlock -->
			</div>
		  </div>
        </div>
		<div class="sidecenter">
            <? // relpaced menu component with html so that we can change .top.menu.php freely ?>
            <div class="bar">
                <ul>
                    <li class="active">
                        <a href="/">Главная</a>
                    </li>
                    <li class="sep"></li><li>
                        <a class="double-string" href="/catalog/">Интернет-магазин</a>
                    </li>
                    <li class="sep"></li><li>
                        <a class="double-string" href="/buy/">Оплата/доставка</a>
                    </li>
                    <li class="sep"></li><li>
                        <a href="/partneram/">Партнерам</a>
                    </li>
                    <li class="sep"></li><li>
                        <a class="double-string" href="/region/">Сервисное обслуживание</a>
                    </li>
                    <li class="sep"></li><li>
                        <a href="/news/">Новости</a>
                    </li>
                    <li class="sep"></li><li>
                        <a href="/contacts/">Контакты</a>
                    </li>
                    <li class="sep"></li><li>
                        <a href="/otzivi/">Отзывы</a>
                    </li>
                </ul>
            </div>
	<?if($APPLICATION->GetcurDir()!="/"):?>
			<h1><?$APPLICATION->ShowTitle(false);?></h1>
	<?endif?>
	
	
	
<?if(preg_match('#/partneram/#' , $APPLICATION->GetCurDir())):?>

<?global $USER;


	$arGroups = CUser::GetUserGroup($USER->GetID());
	
		if((in_array(9, $arGroups))||($USER->IsAdmin()))
			{

			
?>

	<?$APPLICATION->IncludeComponent("bitrix:menu", "partner", Array(
	"ROOT_MENU_TYPE" => "partner",	// Тип меню для первого уровня
	"MENU_CACHE_TYPE" => "Y",	// Тип кеширования
	"MENU_CACHE_TIME" => "36000000",	// Время кеширования (сек.)
	"MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
	"MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
	"MAX_LEVEL" => "1",	// Уровень вложенности меню
	"CHILD_MENU_TYPE" => "partner",	// Тип меню для остальных уровней
	"USE_EXT" => "N",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
	"DELAY" => "N",	// Откладывать выполнение шаблона меню
	"ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
	),
	false
);?>
<br>

	<?$APPLICATION->IncludeComponent("bitrix:menu", "partner", Array(
	"ROOT_MENU_TYPE" => "partner1",	// Тип меню для первого уровня
	"MENU_CACHE_TYPE" => "Y",	// Тип кеширования
	"MENU_CACHE_TIME" => "36000000",	// Время кеширования (сек.)
	"MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
	"MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
	"MAX_LEVEL" => "1",	// Уровень вложенности меню
	"CHILD_MENU_TYPE" => "partner",	// Тип меню для остальных уровней
	"USE_EXT" => "N",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
	"DELAY" => "N",	// Откладывать выполнение шаблона меню
	"ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
	),
	false
);?><br>

<?}?>
<?endif?>