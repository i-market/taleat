<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use App\Layout;
use App\Cart;
use App\Components;
use App\App;
use Core\Session;
use Core\Underscore as _;

switch($_REQUEST['mode']):
    case 'contact_form':
        App::submitContactForm($_REQUEST);
        $msg = 'Ваше сообщение отправлено';
        if (!_::get($_REQUEST, 'is_ajax')) { // fallback
            Session::addFlash(['type' => 'success', 'text' => $msg]);
            LocalRedirect('/');
        }
        echo '<p class="title">'.$msg.'</p>'; // TODO
        break;
    case 'personal/profile':
        Components::showPersonalProfile();
        break;
    case 'partner/profile':
        Components::showPartnerProfile();
        break;
    case 'newsletter_sub':
        Components::showNewsletterSub();
        break;
    case 'header_cart':
        Layout::showHeaderCart();
        break;
    case 'cart/index':
        (new Cart)->index();
        break;
    case 'cartUpdate':
        $APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "basket", Array(
            "PATH_TO_BASKET" => SITE_DIR."personal/cart/",  // Страница корзины
            "PATH_TO_PERSONAL" => SITE_DIR."personal/", // Персональный раздел
            "SHOW_PERSONAL_LINK" => "N",    // Отображать ссылку на персональный раздел
        ), false);
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
