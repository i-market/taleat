<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use App\Layout;

switch($_REQUEST["mode"]):
    case "cartUpdate":
        Layout::showHeaderCart();
    break;
    
    case "buy":
        CModule::IncludeModule('catalog');
        CModule::IncludeModule('sale');
        $dbBasketItems = CSaleBasket::GetList(
                Array(), Array( 
                        "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                        "LID" => SITE_ID,
                        "ORDER_ID" => "NULL",
                        "DELAY" => "Y"
                    ),
            false, false, Array("ID")
            );
        while($dbBasketItem = $dbBasketItems->GetNext()):
            CSaleBasket::Delete($dbBasketItem["ID"]);
        endwhile;
        echo Add2BasketByProductID($_REQUEST["id"]);
    break;
        
    default: break;
endswitch;
