<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Каталог");

use App\View as v;
use App\App;
use Core\Util;

$page = $APPLICATION->GetCurPage();

if ($page == "/catalog/"){
    $APPLICATION->SetPageProperty("keywords", "Сетки к бритвам, зубные насадки к электрическим зубным щеткам, запасные части к Браун, Braun, аксессуары к бытовой технике");
    $APPLICATION->SetPageProperty("description", "Мы предлагаем широкий ассортимент аксессуаров  к бытовой технике  фирмы Braun (Браун)");
    $APPLICATION->SetTitle("Магазин | TALEAT");?>

    <? // TODO catalog index page ?>

<?} else {

    if ($_REQUEST['per_page']):
        switch($_REQUEST["per_page"]):
            case "8": $_SESSION['per_page'] = "8"; break;
            case "16": $_SESSION['per_page'] = "16"; break;
            case "24": $_SESSION['per_page'] = "24"; break;
            case "all": $_SESSION['per_page'] = "500"; break;
            default: $_SESSION['per_page'] = "8"; break;
        endswitch;
    endif;

    if (empty($_SESSION['per_page'])):
        $per_page = "8";
    else:
        $per_page = $_SESSION['per_page'];
    endif;

    $iblock_id=3; // catalog -> furniture
    $arIBlock=GetIBlock($iblock_id);
    if (strlen($_REQUEST["SECTION_CODE"])>0)
    {


        $section_code=fn_getSectionCode($_REQUEST["SECTION_CODE"]);


        $arFilter = Array('IBLOCK_ID'=>$arIBlock["ID"],  'CODE'=>$section_code);
        $db_list = CIBlockSection::GetList(Array($by=>$order), $arFilter, true);
        $arSection = $db_list->GetNext();

        $nav = CIBlockSection::GetNavChain($arSection['IBLOCK_ID'], $arSection['ID']);

        while($arChain=$nav->GetNext())
        {

            $APPLICATION->AddChainItem($arChain["NAME"], fn_get_chainpath($arChain['IBLOCK_ID'], $arChain['ID']));

        }

        $APPLICATION->SetTitle($arSection["NAME"]);
    }
    $arSelect = Array("ID", "NAME", "CODE", "IBLOCK_ID", "IBLOCK_SECTION_ID");
    $arFilter = Array("IBLOCK_ID"=>$arIBlock["ID"], "SECTION_ID"=>$arSection["ID"], "ACTIVE"=>"Y");

    if (strlen($_REQUEST["ELEMENT_CODE"])>0)
    {
        $arFilter["CODE"]= htmlspecialchars($_REQUEST["ELEMENT_CODE"]);


        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

        if ($res->SelectedRowsCount()==1)
        {


            while($ob = $res->GetNextElement()){
                $arItem = $ob->GetFields();

                $APPLICATION->AddChainItem($arItem["NAME"], fn_get_chainpath($arItem["IBLOCK_ID"], $arItem["IBLOCK_SECTION_ID"]).$arItem["CODE"].".html");
                $APPLICATION->SetPageProperty("title", $APPLICATION->GetPageProperty('title')." - ".$arItem["NAME"]);
            }

        }
        else{
            LocalRedirect('/404.php');

        }

    }
    else{
        if($APPLICATION->GetCurPage()!="/catalog/"){
            $ex=explode("/", $APPLICATION->GetCurPage());

            foreach($ex as $e){
                if(strlen(trim($e))>0)
                {
                    $code=$e;
                }

            }

            $arFilter2 = Array('IBLOCK_ID'=>$arIBlock["ID"], "ACTIVE"=>"Y", 'CODE'=>$code);
            $db_list = CIBlockSection::GetList(Array($by=>$order), $arFilter2, true);
            if($db_list->SelectedRowsCount()==0)
            {
                LocalRedirect('/404.php');
            }
        }

    }

    ?>



    <?if($arItem["ID"]):?>

        <? // element page ?>
        <? include Util::joinPath([$_SERVER['DOCUMENT_ROOT'], v::template('partials/catalog/element.php')]) ?>

    <?else:?>

        <? App::getInstance()->assert($_REQUEST["block"]!='none', 'legacy') ?>

        <? // brand page ?>
        <? include Util::joinPath([$_SERVER['DOCUMENT_ROOT'], v::template('partials/catalog/brand.php')]) ?>

        <? // filter, search ?>

        <?if(strlen(trim($_REQUEST["TEXT"]))>0)
        {
            global $arFilt;

            $arFilt[] = array(
                "LOGIC" => "OR",
                array("NAME"=>"%".$_REQUEST["TEXT"]."%"),
                array("PROPERTY_ARTNUMBER" => "%".$_REQUEST["TEXT"]."%"),
                array("PROPERTY_IN_TYPE" => "%".$_REQUEST["TEXT"]."%"),
                array("PROPERTY_IN_MODEL" => "%".$_REQUEST["TEXT"]."%"),
            );


        }

        if(strlen(trim($_REQUEST["prop"]))>0)
        {
            global $arFilt;
            if(trim($_REQUEST["prop"])=='DISCOUNT'){
                $arFilt=array("!PROPERTY_DISCOUNT"=>false, "PROPERTY_PROP_VALUE"=>"Со скидкой");
            }
            else{
                $arFilt=Array("PROPERTY_PROP_VALUE"=>$_REQUEST["prop"]);
            }
        }
        ?>
        <?if($arSection["DEPTH_LEVEL"]>1):?>

            <? // TODO nested sections page ?>
            <? App::getInstance()->assert(false, 'TODO') ?>

            <?$APPLICATION->IncludeComponent(
                "bitrix:catalog.section",
                "cat",
                array(
                    "IBLOCK_TYPE" => "catalog",
                    "IBLOCK_ID" => $iblock_id,
                    "SECTION_ID" => $arSection["ID"],
                    "SECTION_CODE" => "",
                    "SECTION_USER_FIELDS" => array(
                        0 => "",
                        1 => "",
                    ),
                    "ELEMENT_SORT_FIELD" => "id",
                    "ELEMENT_SORT_ORDER" => "asc",
                    "FILTER_NAME" => "arFilt",
                    "FIELD_CODE" => array(
                        0 => "DETAIL_PICTURE",
                        1 => "",
                    ),
                    "INCLUDE_SUBSECTIONS" => "N",
                    "SHOW_ALL_WO_SECTION" => "Y",
                    "PAGE_ELEMENT_COUNT" => $per_page,
                    "LINE_ELEMENT_COUNT" => "2",
                    "PROPERTY_CODE" => array(
                        0 => "",
                        1 => "artikul",
                        2 => "",
                    ),
                    "OFFERS_LIMIT" => "",
                    "SECTION_URL" => "",
                    "DETAIL_URL" => "",
                    "BASKET_URL" => "/personal/basket.php",
                    "ACTION_VARIABLE" => "action",
                    "PRODUCT_ID_VARIABLE" => "id",
                    "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                    "PRODUCT_PROPS_VARIABLE" => "prop",
                    "SECTION_ID_VARIABLE" => "SECTION_ID",
                    "AJAX_MODE" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "Y",
                    "AJAX_OPTION_HISTORY" => "N",
                    "CACHE_TYPE" => "N",
                    "CACHE_TIME" => "36000000",
                    "CACHE_GROUPS" => "N",
                    "META_KEYWORDS" => "-",
                    "META_DESCRIPTION" => "-",
                    "BROWSER_TITLE" => "-",
                    "ADD_SECTIONS_CHAIN" => "N",
                    "DISPLAY_COMPARE" => "N",
                    "SET_TITLE" => "N",
                    "SET_STATUS_404" => "N",
                    "CACHE_FILTER" => "Y",
                    "PRICE_CODE" => array(
                        0 => "BASE",
                    ),
                    "USE_PRICE_COUNT" => "N",
                    "SHOW_PRICE_COUNT" => "1",
                    "PRICE_VAT_INCLUDE" => "N",
                    "PRODUCT_PROPERTIES" => array(
                    ),
                    "USE_PRODUCT_QUANTITY" => "Y",
                    "CONVERT_CURRENCY" => "Y",
                    "CURRENCY_ID" => "RUB",
                    "DISPLAY_TOP_PAGER" => "Y",
                    "DISPLAY_BOTTOM_PAGER" => "Y",
                    "PAGER_TITLE" => "Товары",
                    "PAGER_SHOW_ALWAYS" => "N",
                    "PAGER_TEMPLATE" => "",
                    "PAGER_DESC_NUMBERING" => "N",
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                    "PAGER_SHOW_ALL" => "N",
                    "AJAX_OPTION_ADDITIONAL" => "",
                    "COMPONENT_TEMPLATE" => "cat",
                    "ELEMENT_SORT_FIELD2" => "id",
                    "ELEMENT_SORT_ORDER2" => "desc",
                    "HIDE_NOT_AVAILABLE" => "N",
                    "BACKGROUND_IMAGE" => "-",
                    "SEF_MODE" => "N",
                    "SET_BROWSER_TITLE" => "Y",
                    "SET_META_KEYWORDS" => "Y",
                    "SET_META_DESCRIPTION" => "Y",
                    "SET_LAST_MODIFIED" => "N",
                    "USE_MAIN_ELEMENT_SECTION" => "N",
                    "ADD_PROPERTIES_TO_BASKET" => "Y",
                    "PARTIAL_PRODUCT_PROPERTIES" => "N",
                    "PAGER_BASE_LINK_ENABLE" => "N",
                    "SHOW_404" => "N",
                    "MESSAGE_404" => ""
                ),
                false
            );?>
        <?elseif($arSection["DEPTH_LEVEL"]==1):?>

            <?
            $arFiltere = Array('IBLOCK_ID'=>$iblock_id, 'ACTIVE'=>'Y', 'SECTION_ID'=>$arSection["ID"]);
            $db_list = CIBlockSection::GetList(Array($by=>$order), $arFiltere, true);
            ?>
            <? App::getInstance()->assert($db_list->SelectedRowsCount()!=0, 'legacy') ?>


        <?endif?>


    <?endif?>



<?}?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>