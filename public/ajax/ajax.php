<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use App\Layout;
use App\Cart;
use App\Components;

switch($_REQUEST['mode']):
    case 'partner/profile':
        Components::showPartnerProfile();
        break;
    case 'partner/newsletter_sub':
        Components::showNewsletterSub();
        break;
    case 'cartUpdate':
        Layout::showHeaderCart();
        break;
    case 'cart/index':
        (new Cart)->index();
        break;
    case 'buy':
        CModule::IncludeModule('catalog');
        CModule::IncludeModule('sale');
        $dbBasketItems = CSaleBasket::GetList(
            Array(), Array(
            'FUSER_ID' => CSaleBasket::GetBasketUserID(),
            'LID' => SITE_ID,
            'ORDER_ID' => 'NULL',
            'DELAY' => 'Y'
        ),
            false, false, Array('ID')
        );
        while($dbBasketItem = $dbBasketItems->GetNext()):
            CSaleBasket::Delete($dbBasketItem['ID']);
        endwhile;
        echo Add2BasketByProductID($_REQUEST['id'], $_REQUEST['quantity'] ?: 1);
        break;
    default: break;
endswitch;
