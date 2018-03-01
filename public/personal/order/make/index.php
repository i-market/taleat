<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Заказы");
$APPLICATION->SetPageProperty("layout", "bare");
$APPLICATION->SetPageProperty("body_class", "bg");
?>

<section class="product-registration">
  <div class="wrap">
    <div class="section-title">
      <div class="wrap">
        <div class="section-title-block">
          <h2><? $APPLICATION->ShowTitle(false) ?></h2>
        </div>
      </div>
    </div>
  </div>
  <div class="product-registration-inner">
    <div class="wrap">
        <div class="wrap-sticky">
            <? $APPLICATION->ShowViewContent('bitrix:sale.order.ajax/checkout/summary') ?>
        </div>
        <?$APPLICATION->IncludeComponent(
            "imarket:sale.order.ajax",
            "checkout",
            array(
                "PAY_FROM_ACCOUNT" => "N",
                "COUNT_DELIVERY_TAX" => "N",
                "COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
                "ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
                "ALLOW_AUTO_REGISTER" => "Y",
                "ALLOW_NEW_PROFILE" => $USER->IsAdmin() ? 'Y' : 'N',
                "SEND_NEW_USER_NOTIFY" => "N",
                "DELIVERY_NO_AJAX" => "N",
                "DELIVERY_NO_SESSION" => "N",
                "TEMPLATE_LOCATION" => "popup_with_filter",
                "DELIVERY_TO_PAYSYSTEM" => "d2p",
                "USE_PREPAYMENT" => "N",
                "PROP_1" => array(
                ),
                "PROP_2" => array(
                ),
                "PATH_TO_BASKET" => "/personal/cart/",
                "PATH_TO_PERSONAL" => "/personal/order/",
                "PATH_TO_PAYMENT" => "/personal/order/payment/",
                "PATH_TO_AUTH" => "/auth/",
                "SET_TITLE" => "Y",
                "DISPLAY_IMG_WIDTH" => "90",
                "DISPLAY_IMG_HEIGHT" => "90",
                "COMPONENT_TEMPLATE" => "visual",
                "DISABLE_BASKET_REDIRECT" => "N",
                "PRODUCT_COLUMNS" => array(
                )
            ),
            false
        );?>
    </div>
  </div>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>