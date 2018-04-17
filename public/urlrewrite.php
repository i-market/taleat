<?
$arUrlRewrite = array(
	array(
		"CONDITION" => "#/catalog/(\\S+)/(\\S+).html(\\?*)(\\S*)#",
		"RULE" => "SECTION_CODE=\$1&ELEMENT_CODE=\$2",
		"ID" => "",
		"PATH" => "/catalog/index.php",
	),
	array(
		"CONDITION" => "#/catalog/(\\S+).html(\\?*)(\\S*)#",
		"RULE" => "ELEMENT_CODE=\$1",
		"ID" => "",
		"PATH" => "/catalog/index.php",
	),
	array(
		"CONDITION" => "#^/bitrix/services/ymarket/#",
		"RULE" => "",
		"ID" => "",
		"PATH" => "/bitrix/services/ymarket/index.php",
	),
	array(
		"CONDITION" => "#^/partneram/feed/(\\S+)/\\S*#",
		"RULE" => "ELEMENT_ID=\$1",
		"ID" => "",
		"PATH" => "/partneram/feed_detail.php",
	),
	array(
		"CONDITION" => "#^/partneram/(\\S+)/\\S*#",
		"RULE" => "tab=\$1",
		"ID" => "",
		"PATH" => "/partneram/index.php",
	),
	array(
		"CONDITION" => "#^/personal/order/#",
		"RULE" => "",
		"ID" => "bitrix:sale.personal.order",
		"PATH" => "/personal/order/index.php",
	),
	array(
		"CONDITION" => "#/catalog/(\\S+)/*#",
		"RULE" => "SECTION_CODE=\$1",
		"ID" => "",
		"PATH" => "/catalog/index.php",
	),
	array(
		"CONDITION" => "#^/about/idea/#",
		"RULE" => "",
		"ID" => "bitrix:idea",
		"PATH" => "/about/idea/index.php",
	),
	array(
		"CONDITION" => "#^/articles/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/articles/index.php",
	),
	array(
		"CONDITION" => "#^/videos/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/videos/index.php",
	),
	array(
		"CONDITION" => "#^/store/#",
		"RULE" => "",
		"ID" => "bitrix:catalog.store",
		"PATH" => "/store/index.php",
	),
	array(
		"CONDITION" => "#^/news/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/news/index.php",
	),
);

?>