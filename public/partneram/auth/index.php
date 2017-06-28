<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Авторизация");
?>
<?$APPLICATION->IncludeComponent(
   "imarket:system.auth.authorize",
   "",
   Array(
      "REGISTER_URL" => "",
      "PROFILE_URL" => "",
      "SHOW_ERRORS" => "Y"
   ),
false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>