<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Техническое заключение");

global $filter;

$date = date("Y-m-d", strtotime("-6 month"));

if ($USER->IsAdmin()){
	$filter = array(
		array(
			"LOGIC" => "AND",
			array("PROPERTY_APPROVED"=>"1"),
			array("<=DATE_ACTIVE_FROM" => ConvertTimeStamp(strtotime($date),"FULL")),
		),
	);
}
else{
	$filter = array(
		array(
			"LOGIC" => "AND",
			array("PROPERTY_USER"=>$USER->GetID()),
			array("PROPERTY_APPROVED"=>"1"),
			array("<=DATE_ACTIVE_FROM" => ConvertTimeStamp(strtotime($date),"FULL")),
		),
	);	
}
?>
<?$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "archive",
    Array(
        "DISPLAY_DATE" => "N",
        "DISPLAY_NAME" => "N",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "N",
        "AJAX_MODE" => "N",
        "IBLOCK_TYPE" => "region",
        "IBLOCK_ID" => "10",
        "NEWS_COUNT" => "100",
        "SORT_BY1" => "ID",
        "SORT_ORDER1" => "ASC",
        "SORT_BY2" => "",
        "SORT_ORDER2" => "",
        "FILTER_NAME" => "filter",
        "FIELD_CODE" => Array(""),
        "PROPERTY_CODE" => Array("NUMER", "APPROVED", "FORMA", "STATUS", "SC_NAME", "USER_IMGS"),
        "CHECK_DATES" => "N",
        "DETAIL_URL" => "",
        "PREVIEW_TRUNCATE_LEN" => "",
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "SET_TITLE" => "N",
        "SET_STATUS_404" => "N",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "ADD_SECTIONS_CHAIN" => "N",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "INCLUDE_SUBSECTIONS" => "N",
        "CACHE_TYPE" => "N",
        "CACHE_TIME" => "3600",
        "CACHE_FILTER" => "Y",
        "CACHE_GROUPS" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "PAGER_TITLE" => "",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => ".default",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "N",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_ADDITIONAL" => ""
    )
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>