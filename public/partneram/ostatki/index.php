<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Остатки");
global $USER;
$arGroups = $USER->GetUserGroup($USER->GetID());

?> 
<ul id="ost_download"> <?if((in_array(12, $arGroups))||(in_array(1, $arGroups))):?>
  <li> <a href="/partneram/ostatki/braun/" >Braun (P&amp;G-красота)</a></li>
 <?endif?> <?if((in_array(10, $arGroups))||(in_array(1, $arGroups))):?>
  <li> <a href="/partneram/ostatki/de-longhi/" >Braun (De'Longhi-кухня)</a></li>
 <?endif?> <?if((in_array(11, $arGroups))||(in_array(1, $arGroups))):?>
  <li> <a href="/partneram/ostatki/electrolux/" >Babyliss</a></li>
 <?endif?> 
  <div class="clear"></div>
 </ul>
 <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>