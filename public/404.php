<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

use App\App;
use Core\Env;

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Ошибка 404");

if (App::env() !== Env::DEV) {
    App::getInstance()->assert(false, "404 not found: {$_SERVER['REQUEST_URI']}");
}
?>
<h1>Ошибка 404</h1>
<p>Страница не найдена или была удалена. Пожалуйста, проверьте URL-адрес или воспользуйтесь поиском.</p>
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

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>