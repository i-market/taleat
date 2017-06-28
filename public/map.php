<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Карта сайта");
?>

<h1>Карта сайта</h1>
<?
$APPLICATION->IncludeComponent("bitrix:main.map", ".default", array(
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "36000000",
	"SET_TITLE" => "Y",
	"LEVEL"	=>	"3",
	"COL_NUM"	=>	"2",
	"SHOW_DESCRIPTION" => "N"
	),
	false
);

?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>