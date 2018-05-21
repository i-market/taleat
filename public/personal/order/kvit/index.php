<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

\App\Order::handleInvoice($_REQUEST['id']);