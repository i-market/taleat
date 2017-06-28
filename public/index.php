<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Сетки к бритвам, зубные насадки к электрическим зубным щеткам, запасные части к Браун, Braun, аксессуары к бытовой технике");
$APPLICATION->SetPageProperty("description", "Мы предлагаем широкий ассортимент аксессуаров  к бытовой технике  фирмы Braun (Браун)");
$APPLICATION->SetTitle("Главная | TALEAT");
?>

<?
global $arFiltNew;

$arFiltNew=array("PROPERTY_PROP_VALUE"=>"Новинки");

?>

<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
	"AREA_FILE_SHOW" => "file",
	"PATH" => "/include/text_in_main_page2.php",
	"EDIT_TEMPLATE" => ""
	),
	false
);?>
<br>
<br>
<table class="table-info-main">
	<tbody>
		<tr>
			<td>
				<div class="bordBlock-wrap custom-bordblock">
					<div class="bordBlock_title">
						<a href="/catalog/">Магазин</a>
					</div>
					<div class="bordBlock">
						<div><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
							"AREA_FILE_SHOW" => "file",
							"PATH" => "/include/text_block1.php",
							"EDIT_TEMPLATE" => ""
							),
							false
						);?></div>
					</div>
				</div>
			</td>
			
			<td>
				<div class="bordBlock-wrap custom-bordblock">
					<div class="bordBlock_title">
						<a style="line-height: 24px !important;" href="/region/">Сервисное обслуживание</a>
					</div>
					<div class="bordBlock">
						<div><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
							"AREA_FILE_SHOW" => "file",
							"PATH" => "/include/text_block2.php",
							"EDIT_TEMPLATE" => ""
							),
							false
						);?></div>
					</div>
				</div>
			</td>

			<td>
				<div class="bordBlock-wrap custom-bordblock">
					<div class="bordBlock_title">
						<a href="/priemnie-punkti/">Приемные пункты</a>
					</div>
					<div class="bordBlock">
						<div><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
							"AREA_FILE_SHOW" => "file",
							"PATH" => "/include/text_block3.php",
							"EDIT_TEMPLATE" => ""
							),
							false
						);?></div>
					</div>
				</div>
			</td>
			
			<td>
				<div class="bordBlock-wrap custom-bordblock">
					<div class="bordBlock_title">
						<a href="/buy/">Информация</a>
					</div>
					<div class="bordBlock">
						<div><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
							"AREA_FILE_SHOW" => "file",
							"PATH" => "/include/text_block4.php",
							"EDIT_TEMPLATE" => ""
							),
							false
						);?></div>
					</div>
				</div>
			</td>
		</tr>
	</tbody>
</table>
<?/*
 <?$APPLICATION->IncludeComponent("bitrix:news.list", "novinka", array(
	"IBLOCK_TYPE" => "catalog",
	"IBLOCK_ID" => "3",
	"NEWS_COUNT" => "",
	"SORT_BY1" => "SORT",
	"SORT_ORDER1" => "DESC",
	"SORT_BY2" => "SORT",
	"SORT_ORDER2" => "ASC",
	"FILTER_NAME" => "arFiltNew",
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
	"DISPLAY_BOTTOM_PAGER" => "N",
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


<?
global $arFiltNew;

$arFiltPop=array("PROPERTY_PROP_VALUE"=>"Популярные");

?>

 <?$APPLICATION->IncludeComponent("bitrix:news.list", "pop_tovar", array(
	"IBLOCK_TYPE" => "catalog",
	"IBLOCK_ID" => "3",
	"NEWS_COUNT" => "",
	"SORT_BY1" => "SORT",
	"SORT_ORDER1" => "DESC",
	"SORT_BY2" => "SORT",
	"SORT_ORDER2" => "ASC",
	"FILTER_NAME" => "arFiltPop",
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
	"DISPLAY_BOTTOM_PAGER" => "N",
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


<?
global $arFiltNew;

$arFiltDic=array("!PROPERTY_DISCOUNT"=>false, "PROPERTY_PROP_VALUE"=>"Со скидкой");

?>

 <?$APPLICATION->IncludeComponent("bitrix:news.list", "discount", Array(
	"IBLOCK_TYPE" => "catalog",	// Тип информационного блока (используется только для проверки)
	"IBLOCK_ID" => "3",	// Код информационного блока
	"NEWS_COUNT" => "",	// Количество новостей на странице
	"SORT_BY1" => "SORT",	// Поле для первой сортировки новостей
	"SORT_ORDER1" => "DESC",	// Направление для первой сортировки новостей
	"SORT_BY2" => "SORT",	// Поле для второй сортировки новостей
	"SORT_ORDER2" => "ASC",	// Направление для второй сортировки новостей
	"FILTER_NAME" => "arFiltDic",	// Фильтр
	"FIELD_CODE" => array(	// Поля
		0 => "DETAIL_PICTURE",
		1 => "",
	),
	"PROPERTY_CODE" => array(	// Свойства
		0 => "",
		1 => "LINK",
		2 => "space",
		3 => "",
	),
	"CHECK_DATES" => "N",	// Показывать только активные на данный момент элементы
	"DETAIL_URL" => "",	// URL страницы детального просмотра (по умолчанию - из настроек инфоблока)
	"AJAX_MODE" => "N",	// Включить режим AJAX
	"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
	"AJAX_OPTION_STYLE" => "N",	// Включить подгрузку стилей
	"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
	"CACHE_TYPE" => "N",	// Тип кеширования
	"CACHE_TIME" => "3600",	// Время кеширования (сек.)
	"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
	"CACHE_GROUPS" => "Y",	// Учитывать права доступа
	"PREVIEW_TRUNCATE_LEN" => "",	// Максимальная длина анонса для вывода (только для типа текст)
	"ACTIVE_DATE_FORMAT" => "d.m.Y",	// Формат показа даты
	"SET_TITLE" => "N",	// Устанавливать заголовок страницы
	"SET_STATUS_404" => "N",	// Устанавливать статус 404, если не найдены элемент или раздел
	"INCLUDE_IBLOCK_INTO_CHAIN" => "N",	// Включать инфоблок в цепочку навигации
	"ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
	"HIDE_LINK_WHEN_NO_DETAIL" => "N",	// Скрывать ссылку, если нет детального описания
	"PARENT_SECTION" => "",	// ID раздела
	"PARENT_SECTION_CODE" => "",	// Код раздела
	"DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
	"DISPLAY_BOTTOM_PAGER" => "N",	// Выводить под списком
	"PAGER_TITLE" => "",	// Название категорий
	"PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
	"PAGER_TEMPLATE" => "",	// Название шаблона
	"PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
	"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// Время кеширования страниц для обратной навигации
	"PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
	"DISPLAY_DATE" => "Y",	// Выводить дату элемента
	"DISPLAY_NAME" => "Y",	// Выводить название элемента
	"DISPLAY_PICTURE" => "Y",	// Выводить изображение для анонса
	"DISPLAY_PREVIEW_TEXT" => "Y",	// Выводить текст анонса
	"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
	),
	false
);?>
*/?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
