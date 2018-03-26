<?
// composer
require $_SERVER['DOCUMENT_ROOT'].'/local/vendor/autoload.php';

use App\App;
use App\Email;
use App\Iblock;
use App\Order;
use App\OrderStatus;
use App\User;
use Bitrix\Sale\Delivery\Services\Manager;
use Core\Env;
use Core\Strings as str;

App::getInstance()->init();

const IB_REPORTS = 10;
CModule::IncludeModule('sale');
CModule::IncludeModule('iblock');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface/CArcLineWorkPicture.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface/SendMessage.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface/func.php');

// see also: `App\EventHandlers`

AddEventHandler("iblock", "OnBeforeIBlockSectionAdd", Array("MyResizeSectionPicturesHandlers", "ResizeElementProperty"));
AddEventHandler("iblock", "OnBeforeIBlockSectionUpdate", Array("MyResizeSectionPicturesHandlers", "ResizeElementProperty"));
AddEventHandler("iblock", "OnAfterIBlockSectionAdd", Array("MyResizeSectionPicturesHandlers", "ClearTempElementProperty"));
AddEventHandler("iblock", "OnAfterIBlockSectionUpdate", Array("MyResizeSectionPicturesHandlers", "ClearTempElementProperty"));

AddEventHandler("iblock", "OnBeforeIBlockElementAdd", Array("MyResizePicturesHandlers", "ResizeElementProperty"));
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("MyResizePicturesHandlers", "ResizeElementProperty"));
AddEventHandler("iblock", "OnAfterIBlockElementAdd", Array("MyResizePicturesHandlers", "ClearTempElementProperty"));
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", Array("MyResizePicturesHandlers", "ClearTempElementProperty"));
AddEventHandler("main", "OnAfterUserUpdate", Array("MyUpdateUser", "UpdateUserSendMessage"));

AddEventHandler("main", "OnAfterUserAdd", "OnAfterUserAddHandler");

AddEventHandler("main", "OnAdminListDisplay", "MyOnAdminListDisplay");
function MyOnAdminListDisplay(&$list)
{
	if ($list->table_id == 'tbl_iblock_list_195dbc2ffd41f99351ac43eb2f5b8eac'){
		$list->aVisibleHeaders["UPDATE_TZ"] =
			array(
				"id" => "UPDATE_TZ",
				"content" => 'Обновление ТЗ',
				"sort" => "UPDATE_TZ",
				"default" => true,
				"align" => "left",
			);

		$list->arVisibleColumns[]= 'UPDATE_TZ';

		foreach ($list->aRows as $row) {
			$link = "http://".$_SERVER['SERVER_NAME']."/partneram/babyliss/tech-form/updateTz.php?ID=".$row->arRes['ID'];
			$row->addField(
				'UPDATE_TZ',
				'<a href="'.$link.'">Обновить</a>'
			);
		}
	}
}

function OnAfterUserAddHandler(&$arFields)
{
   if (0 < $arFields["ID"]){
//      CUser::SendUserInfo($arFields["ID"], SITE_ID, "");
   }
   
   return $arFields;
}

AddEventHandler("sale", "OnSaleStatusOrder", Array("myClass", "StatusUpdate"));
class myClass{
    static function payUrl($arOrder) {
        $ID = $arOrder['ID'];
        $url = null;
        if ($arOrder["PAY_SYSTEM_ID"] == 7):
            CSalePaySystemAction::InitParamArrays($arOrder, $arOrder["ID"]);
            $mrh_login =  CSalePaySystemAction::GetParamValue("ShopLogin");
            $mrh_pass1 =  CSalePaySystemAction::GetParamValue("ShopPassword");
            $inv_id    = $arOrder["ID"];
            $inv_desc  = "desc";
            $out_summ  = $arOrder["PRICE"];
            $crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1");
            $url = "https://auth.robokassa.ru/Merchant/Index.aspx?MrchLogin=$mrh_login&"."OutSum=$out_summ&InvId=$inv_id&Desc=$inv_desc&SignatureValue=$crc";
        elseif($arOrder["PAY_SYSTEM_ID"] == 5):
            $url = "http://".$_SERVER["SERVER_NAME"]."/personal/order/bill/?ORDER_ID=".$ID;
        elseif($arOrder["PAY_SYSTEM_ID"] == 8):
            $url = "http://".$_SERVER["SERVER_NAME"]."/personal/order/yandex-kassa/?ORDER_ID=".$ID;
        endif;
        return $url;
    }

    static function payAction($arOrder) {
        $url = self::payUrl($arOrder);
        return $arOrder["PAY_SYSTEM_ID"] == 5
            ? '<br>Квитанция на оплату заказа – <a href="'.$url.'&download=1">скачать</a><br>'
            : (isset($url) ? '<br><a href="'.$url.'">Оплатить</a><br>' : ''); // TODO better text for other payment methods;
    }

    static function shipment($ID) {
        $ret = null;
        $order = \Bitrix\Sale\Order::load($ID);
        $shipmentCollection = $order->getShipmentCollection();
        /** @var \Bitrix\Sale\Shipment $shipment */
        foreach ($shipmentCollection as $shipment):
            if($shipment->isSystem()) continue;
            $ret = $shipment;
        endforeach;
        return $ret;
    }

    function StatusUpdate($ID, $val)
    {
        $agentsCfg = [
            'getFeedback' => [
                'requestAfter' => in_array(App::env(), [Env::DEV, Env::STAGE])
                    ? (new \DateTime())->modify('+1 second')
                    : (new \DateTime())->modify('+10 days')
            ],
            'unpaidOrderReminder' => [
                'remindAfter' => in_array(App::env(), [Env::DEV, Env::STAGE])
                    ? (new \DateTime())->modify('+1 second')
                    : (new \DateTime())->modify('+3 days')
            ]
        ];

        // Нет на складе
        if ($val == "O"):
            $arOrder = CSaleOrder::GetByID($ID);
            $db_props = CSaleOrderPropsValue::GetOrderProps($ID);
            while ($arProps = $db_props->Fetch())
            {
                if($arProps["CODE"] == "EMAIL")
                    $arFields["EMAIL"] = $arProps["VALUE"];
                if($arProps["CODE"] == "FAM")
                    $arFields["FAM"] = $arProps["VALUE"];
                if($arProps["CODE"] == "IMYA")
                    $arFields["IMYA"] = $arProps["VALUE"];
                if($arProps["CODE"] == "OTCHESTVO")
                    $arFields["OTCHESTVO"] = $arProps["VALUE"];
                if($arProps["CODE"] == "SROK")
                    $arFields["SROK"] = $arProps["VALUE"];
            }
            $arFields["SALE_EMAIL"] = COption::GetOptionString("sale", "order_email");
            $arFields["ORDER_ID"] = $ID;
            $arFields["ORDER_DATE"] = $arOrder["DATE_INSERT"];
            $arFields["COMMENTS"] = !str::isEmpty($arFields["SROK"]) ?
                "Ожидаемый срок поставки товара: ".$arFields["SROK"]
                : '';
            $arFields['FULL_NAME'] = User::formatFullName($arFields['FAM'], $arFields['IMYA'], $arFields['OTCHESTVO']);
            $arFields['STATUS'] = CSaleStatus::GetByID($val)['NAME'];
            CEvent::SendImmediate("STATUS_OUT_OF_STOCK", "s1", $arFields);
        endif;

        // Оплачен
        if ($val == "P"):
            $arFields = array();
            $arOrder = CSaleOrder::GetByID($ID);
            $db_props = CSaleOrderPropsValue::GetOrderProps($ID);
            while ($arProps = $db_props->Fetch()){
                //print_r($arProps);
                if($arProps["CODE"] == "EMAIL")
                    $arFields["EMAIL"] = $arProps["VALUE"];
                if($arProps["CODE"] == "FAM")
                    $arFields["FAM"] = $arProps["VALUE"];
                if($arProps["CODE"] == "IMYA")
                    $arFields["IMYA"] = $arProps["VALUE"];
                if($arProps["CODE"] == "OTCHESTVO")
                    $arFields["OTCHESTVO"] = $arProps["VALUE"];
            }

            $arFields["SALE_EMAIL"] = COption::GetOptionString("sale", "order_email");
            $arFields["COMMENTS"] = $arOrder["COMMENTS"];
            $arFields["ORDER_ID"] = $ID;
            $arFields["ORDER_DATE"] = $arOrder["DATE_INSERT"];
            $arFields['FULL_NAME'] = User::formatFullName($arFields['FAM'], $arFields['IMYA'], $arFields['OTCHESTVO']);
            $arFields['STATUS'] = CSaleStatus::GetByID($val)['NAME'];
            $arFields['DELIVERY'] = $arOrder['DELIVERY_ID'] == Order::LOCAL_PICKUP
                ? join("<br>\n", [
                    'Передан на приемный пункт по адресу:',
                    '"Сервисный центр на Селезневке"',
                    '',
                    'м. Достоевская, м Новослободская',
                    'г. Москва, ул. Селезневская, д. 30, корп. 1',
                    'пн.-пт.: с 10: до 20:00',
                    'сб.: с 10:00 до 17:00',
                    'вск.: выходной'
                ])
                : '';

            CEvent::SendImmediate("STATUS_PAY", "s1", $arFields);
        endif;

        // Отправлен
        if ($val == "F"):
            $arFields = array();
            $arOrder = CSaleOrder::GetByID($ID);
            $db_props = CSaleOrderPropsValue::GetOrderProps($ID);
            while ($arProps = $db_props->Fetch()){
                //print_r($arProps);
                if($arProps["CODE"] == "EMAIL")
                    $arFields["EMAIL"] = $arProps["VALUE"];
                if($arProps["CODE"] == "FAM")
                    $arFields["FAM"] = $arProps["VALUE"];
                if($arProps["CODE"] == "IMYA")
                    $arFields["IMYA"] = $arProps["VALUE"];
                if($arProps["CODE"] == "OTCHESTVO")
                    $arFields["OTCHESTVO"] = $arProps["VALUE"];
            }

            $shipment = self::shipment($ID);
            $arFields['TRACKING'] = '';
            if ($shipment) {
                $trackingNumber = $shipment->getField('TRACKING_NUMBER');
                if ($trackingNumber) {
                    $orgInfo = Order::deliveryServiceOrgInfo($arOrder['DELIVERY_ID']);
                    $name = $orgInfo
                        ? '<a href="'.$orgInfo['url'].'">'.$orgInfo['name'].'</a>'
                        : $shipment->getField('DELIVERY_NAME');
                    $arFields['TRACKING'] = "Отслеживать статус отправления Вы можете через трек-номер {$trackingNumber} на сайте {$name}.";
                }
            }
            $arFields["SALE_EMAIL"] = COption::GetOptionString("sale", "order_email");
            $arFields["COMMENTS"] = $arOrder["COMMENTS"];
            $arFields["ORDER_ID"] = $ID;
            $arFields["ORDER_DATE"] = $arOrder["DATE_INSERT"];
            $arFields['FULL_NAME'] = User::formatFullName($arFields['FAM'], $arFields['IMYA'], $arFields['OTCHESTVO']);

            if ($arOrder['DELIVERY_ID'] != Order::LOCAL_PICKUP) {
                CEvent::SendImmediate("STATUS_SEND", "s1", $arFields);
            }

            $date = $agentsCfg['getFeedback']['requestAfter'];
            $dateAgent = $date->format('d.m.Y H:i:s');
            CAgent::AddAgent("GetFeedback(".$ID.");", "main", "N", 86400, "", "Y", $dateAgent, 100);
        endif;


        // Заказ активен (принят, ожидается оплата)
        if ($val == "A"):
            $arOrder = CSaleOrder::GetByID($ID);
            $url = self::payUrl($arOrder);
            CSaleOrder::CommentsOrder($arOrder["ID"], $url);

            $db_props = CSaleOrderPropsValue::GetOrderProps($ID);
            while ($arProps = $db_props->Fetch())
            {
                if($arProps["CODE"] == "EMAIL")
                    $arFields["EMAIL"] = $arProps["VALUE"];
                if($arProps["CODE"] == "FAM")
                    $arFields["FAM"] = $arProps["VALUE"];
                if($arProps["CODE"] == "IMYA")
                    $arFields["IMYA"] = $arProps["VALUE"];
                if($arProps["CODE"] == "OTCHESTVO")
                    $arFields["OTCHESTVO"] = $arProps["VALUE"];
            }
            $arFields["SALE_EMAIL"] = COption::GetOptionString("sale", "order_email");
            $arFields["COMMENTS"]  = "<p>Заказанные Вами товары у нас в наличии</p>";
            $arFields["COMMENTS"] .= "<p>Общая сумма заказа с доставкой составляет: ".$arOrder["PRICE"]." руб.</p>";
            //$arFields["COMMENTS"] .= "<p>Вы можете оплатить свой заказ перейдя по ссылке: <a href='".$url."'>".$url."</a></p>";
            $arFields["COMMENTS"] .= "<p>Для оплаты необходимо войти в личный кабинет под логином и паролем.</p>";
            $arFields["ORDER_ID"] = $ID;
            $arFields["ORDER_DATE"] = $arOrder["DATE_INSERT"];

            $items = iter\toArray(Iblock::iter((new CSaleBasket())->GetList([], ['ORDER_ID' => $arOrder['ID']])));
            $arFields = array_merge($arFields, [
                'FULL_NAME' => User::formatFullName($arFields['FAM'], $arFields['IMYA'], $arFields['OTCHESTVO']),
                'ORDER_LIST' => Email::orderListStr($items),
                'DELIVERY_PRICE' => SaleFormatCurrency($arOrder['PRICE_DELIVERY'], $arOrder['CURRENCY']),
                'DELIVERY_NAME' => Manager::getServiceByCode($arOrder['DELIVERY_ID'])->getName(),
                'PRICE' => SaleFormatCurrency($arOrder['PRICE'], $arOrder['CURRENCY']),
                'PAY_ACTION' => self::payAction($arOrder)
            ]);

            CEvent::SendImmediate("STATUS_ORDER_ACTIVE", "s1", $arFields);

            $date = $agentsCfg['unpaidOrderReminder']['remindAfter'];
            $dateAgent = $date->format('d.m.Y H:i:s');
            CAgent::AddAgent("UnpaidOrderReminder(".$ID.");", "main", "N", 86400, "", "Y", $dateAgent, 100);
        endif;

        // Товар поступил на склад
        if($val == "S"):
            $arOrder = CSaleOrder::GetByID($ID);
            $db_props = CSaleOrderPropsValue::GetOrderProps($ID);
            while ($arProps = $db_props->Fetch())
            {
                if($arProps["CODE"] == "EMAIL")
                    $arFields["EMAIL"] = $arProps["VALUE"];
                if($arProps["CODE"] == "FAM")
                    $arFields["FAM"] = $arProps["VALUE"];
                if($arProps["CODE"] == "IMYA")
                    $arFields["IMYA"] = $arProps["VALUE"];
                if($arProps["CODE"] == "OTCHESTVO")
                    $arFields["OTCHESTVO"] = $arProps["VALUE"];
            }
            $arFields["SALE_EMAIL"] = COption::GetOptionString("sale", "order_email");
            $arFields["ORDER_ID"] = $ID;
            $arFields["ORDER_DATE"] = $arOrder["DATE_INSERT"];
            $arFields['FULL_NAME'] = User::formatFullName($arFields['FAM'], $arFields['IMYA'], $arFields['OTCHESTVO']);
            $arBasketItems = CSaleBasket::GetList(Array(), Array("ORDER_ID"=>$ID), false, false, Array("ID", "PRODUCT_ID", "NAME"));
            /*
            $arFields["PRODUCTS"] = "";
            while($arBasketItem = $arBasketItems->GetNext()):
                $arItem = CIBlockElement::GetList(Array(), Array("ID"=>$arBasketItem["PRODUCT_ID"]), false, false, Array("ID", "IBLOCK_SECTION_ID", "CODE", "IBLOCK_ID"))->GetNext();
                $arItem["DETAIL_PAGE_URL"]=fn_get_chainpath($arItem["IBLOCK_ID"], $arItem["IBLOCK_SECTION_ID"]).$arItem["CODE"].".html";
                $arFields["PRODUCTS"] .= '<a href="http://taleat.ru/catalog/'.$arItem["DETAIL_PAGE_URL"].'">'.$arBasketItem["NAME"].'</a><br />';
            endwhile;
            */
            $arFields["PRODUCTS"] = Email::orderListStr(Iblock::iter($arBasketItems));
            CEvent::SendImmediate("STATUS_FILL_STOCK", "s1", $arFields);
        endif;
    }
}

class MyUpdateUser
{
    function UpdateUserSendMessage(&$arFields){
    global $USER;
    if($USER->IsAdmin())
        {
            if($arFields["ACTIVE"]=='Y')
                {

                    $arSendFields = array(
                        "LOGIN" => $arFields['LOGIN'],
                        "EMAIL" => $arFields['EMAIL'],
                        );

                    $send=CEvent::Send('USER_PARTNER',"s1", $arSendFields);

                }
        }
    }
}

$arPicOptValues=array();
$arPicOptValues[0]=array('item_small','WIDTH',100);
$arPicOptValues[1]=array('item_cart_small','HEIGHT',100);
$arPicOptValues[2]=array('item_list','WIDTH',78);
$arPicOptValues[3]=array('item_detail','HEIGHT',130);
$arPicOptValues[4]=array('item_cart','WIDTH',130);
$arPicOptValues[5]=array('item_cart_h','WIDTH',200);
$arPicOptValues[6]=array('item_cart_fan','WIDTH',280);
$arPicOptValues[7]=array('item_cart_fans','HEIGHT',700);
$arIBlockPictOpt[3]=array(12,36,37);
$clWorkPicture=new CArcLineWorkPicture($arPicOptValues,$arIBlockPictOpt, Array());

class MyResizeSectionPicturesHandlers{
    function ResizeElementProperty(&$arFields){
        global $APPLICATION;
        global $clWorkPicture;

        foreach ($clWorkPicture->arIBlockSectionPictOpt as $iblock_key => $arIBlockValue){
            if( array_key_exists($clWorkPicture->arIBlockSectionPictOpt[$iblock_key][0],$arFields)){
                if ($arFields['IBLOCK_ID']<1) {$arFields['IBLOCK_ID']=$iblock_key;}

                $piccount=0;
                $optpiccount=0;

                foreach($arFields[$clWorkPicture->arIBlockSectionPictOpt[$iblock_key][0]] as $key => $arFile){
                    $arFile=$arFields[$clWorkPicture->arIBlockSectionPictOpt[$iblock_key][0]][$key];
                    if (array_key_exists('VALUE',$arFile)) {$arFile=$arFile['VALUE'];}
                    $clWorkPicture->fn_resizepicture($arFields,$arFile,$clWorkPicture->arIBlockPictOpt[$iblock_key][1],$piccount,true);
                    $piccount++;
                }

            }
        }
    }
    function ClearTempElementProperty(&$arFields){
        global $clWorkPicture;
        if (isset($clWorkPicture) && is_object($clWorkPicture)) {
            $clWorkPicture->fn_delete_temp_picture();
        }
    }
}

class MyResizePicturesHandlers
{
    function ResizeElementProperty(&$arFields)
    {
        global $APPLICATION;
        global $clWorkPicture;

        foreach ($clWorkPicture->arIBlockPictOpt as $iblock_key => $arIBlockValue)
        {
            if(($arFields["IBLOCK_ID"] == $iblock_key) && (strlen($arFields["DETAIL_PICTURE"]["tmp_name"]) > 0)){
                $clWorkPicture->fn_resizepicture($arFields,$arFields["DETAIL_PICTURE"],$clWorkPicture->arIBlockPictOpt[$iblock_key][2],0);
            }

            if($arFields["IBLOCK_ID"] == $iblock_key&&is_array($arFields["PROPERTY_VALUES"])
                &&array_key_exists($clWorkPicture->arIBlockPictOpt[$iblock_key][0],$arFields["PROPERTY_VALUES"]))
            {
                $piccount=0;
                $optpiccount=0;

            foreach($arFields["PROPERTY_VALUES"][$clWorkPicture->arIBlockPictOpt[$iblock_key][0]] as $key => $arFile)
            {

                $arFile=$arFields["PROPERTY_VALUES"][$clWorkPicture->arIBlockPictOpt[$iblock_key][0]][$key];
                if (array_key_exists('VALUE',$arFile)) {$arFile=$arFile['VALUE'];}
                 $clWorkPicture->fn_resizepicture($arFields,$arFile,$clWorkPicture->arIBlockPictOpt[$iblock_key][1],$piccount);
                $piccount++;
            }

          }
        }
    }
    function ClearTempElementProperty(&$arFields)
    {
        global $clWorkPicture;
        if (isset($clWorkPicture) && is_object($clWorkPicture)) {
            $clWorkPicture->fn_delete_temp_picture();
        }
    }
}

function GetFeedback($ID) {
    $db_props = CSaleOrderPropsValue::GetOrderProps($ID);
    $arFields = [];
    while ($arProps = $db_props->Fetch())
    {
        if($arProps["CODE"] == "EMAIL")
            $arFields["EMAIL"] = $arProps["VALUE"];
        if($arProps["CODE"] == "FAM")
            $arFields["FAM"] = $arProps["VALUE"];
        if($arProps["CODE"] == "IMYA")
            $arFields["IMYA"] = $arProps["VALUE"];
        if($arProps["CODE"] == "OTCHESTVO")
            $arFields["OTCHESTVO"] = $arProps["VALUE"];
    }
    $arFields['ORDER_ID'] = $ID;
    $arFields['FULL_NAME'] = User::formatFullName($arFields['FAM'], $arFields['IMYA'], $arFields['OTCHESTVO']);
    $arFields["SALE_EMAIL"] = COption::GetOptionString("sale", "order_email");
    CEvent::Send("GET_FEEDBACK", "s1", $arFields);
}

function UnpaidOrderReminder($ID) {
    $order = CSaleOrder::GetByID($ID);
    // if the status didn't change
    if ($order['STATUS_ID'] === OrderStatus::ACCEPTED) {
        $db_props = Iblock::iter(CSaleOrderPropsValue::GetOrderProps($ID));
        $arFields = [];
        foreach ($db_props as $arProps) {
            if($arProps["CODE"] == "EMAIL")
                $arFields["EMAIL"] = $arProps["VALUE"];
            if($arProps["CODE"] == "FAM")
                $arFields["FAM"] = $arProps["VALUE"];
            if($arProps["CODE"] == "IMYA")
                $arFields["IMYA"] = $arProps["VALUE"];
            if($arProps["CODE"] == "OTCHESTVO")
                $arFields["OTCHESTVO"] = $arProps["VALUE"];
        }
        $arFields['FULL_NAME'] = User::formatFullName($arFields['FAM'], $arFields['IMYA'], $arFields['OTCHESTVO']);
        $arFields["SALE_EMAIL"] = COption::GetOptionString("sale", "order_email");
        $arBasketItems = CSaleBasket::GetList(Array(), Array("ORDER_ID"=>$ID), false, false, Array("ID", "PRODUCT_ID", "NAME"));
        $arFields["ORDER_LIST"] = Email::orderListStr(Iblock::iter($arBasketItems));
        $arFields['DELIVERY_PRICE'] = SaleFormatCurrency($order['PRICE_DELIVERY'], $order['CURRENCY']);
        $arFields['PRICE'] = SaleFormatCurrency($order['PRICE'], $order['CURRENCY']);
        $arFields['PAY_ACTION'] = myClass::payAction($order);
        CEvent::Send("UNPAID_ORDER_REMINDER", App::SITE_ID, $arFields);
    }
}

function file_force_download($file) {
  if (file_exists($file)) {
    if (ob_get_level()) {
      ob_end_clean();
    }
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file).'"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
  }
}

AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "changeStatus");
function changeStatus(&$arFields) {
    if($arFields["IBLOCK_ID"] == IB_REPORTS):
        $newStatus = $arFields["PROPERTY_VALUES"]["58"][0]["VALUE"];
        $arItem = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>$arFields["IBLOCK_ID"], "ID"=>$arFields["ID"]), false, false, Array("ID", "PROPERTY_STATUS", "PROPERTY_USER", "PROPERTY_NUMER"))->GetNext();
        if($newStatus != $arItem["PROPERTY_STATUS_ENUM_ID"]):
            $arPropStatus = CIBlockPropertyEnum::GetByID($newStatus);
			if ($arPropStatus['VALUE'] != 'Подтверждено'){
				$arUser = CUser::GetList(($by = "sort"), ($order = "asc"), Array("ID"=>$arItem["PROPERTY_USER_VALUE"]), Array("SELECT"=>Array("EMAIL")))->GetNext();
				$arMailFields = Array(
					"EMAIL" => $arUser["EMAIL"],
					"STATUS" => $arPropStatus["VALUE"],
					"NUMBER" => $arItem["PROPERTY_NUMER_VALUE"]
				);
				CEvent::Send("CHANGE_STATUS_TZ", "s1", $arMailFields);
			}
        endif;
    endif;
}

class Forms {
    /** @deprecated */
    const PERSONAL_DATA_ERROR = 'Согласие на обработку персональных данных обязательно!';

    /** @deprecated */
    static function validateTermsAgreement($params) {
        return !isset($params['AGREED_PERSONAL_DATA'])
            ? [self::PERSONAL_DATA_ERROR]
            : [];
    }
}
?>