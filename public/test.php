<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
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
_c($dbBasketItem);
endwhile;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");