<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Каталог");
$APPLICATION->SetPageProperty('layout', 'catalog');

use App\Catalog;
use App\View as v;
use App\App;
use Core\Util;
use Core\Underscore as _;

$page = $APPLICATION->GetCurPage();
$iblock_id=3; // products
if ($_REQUEST['per_page']):
    $_SESSION['per_page'] = v::get(Catalog::$perPageOpts, $_REQUEST['per_page'], Catalog::$perPageDefault);
endif;
if (empty($_SESSION['per_page'])):
    $per_page = Catalog::$perPageDefault;
else:
    $per_page = $_SESSION['per_page'];
endif;

if ($page == "/catalog/"){
    $APPLICATION->SetPageProperty("keywords", "Сетки к бритвам, зубные насадки к электрическим зубным щеткам, запасные части к Браун, Braun, аксессуары к бытовой технике");
    $APPLICATION->SetPageProperty("description", "Мы предлагаем широкий ассортимент аксессуаров  к бытовой технике  фирмы Braun (Браун)");
    $APPLICATION->SetTitle("Магазин | TALEAT");?>

    <? $arSection = [] ?>
    <? // index page ?>
    <? include Util::joinPath([$_SERVER['DOCUMENT_ROOT'], v::template('partials/catalog/section.php')]) ?>

<?} else {


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
                $APPLICATION->SetPageProperty("title", join(' - ', _::clean([$APPLICATION->GetPageProperty('title'), $arItem["NAME"]])));
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

        <?
        // TODO refactor
        $childrenCount = CIBlockSection::GetList([], [
            'IBLOCK_ID' => $iblock_id,
            'SECTION_ID' => $arSection['ID'],
            'ACTIVE' => 'Y'
        ])->SelectedRowsCount();
        $isLeafSection = $childrenCount === 0;
        ?>
        <? if (!$isLeafSection): ?>

            <? // TODO rename (brand -> section) ?>
            <? // brand page ?>
            <? include Util::joinPath([$_SERVER['DOCUMENT_ROOT'], v::template('partials/catalog/brand.php')]) ?>

            <?
            $arFiltere = Array('IBLOCK_ID'=>$iblock_id, 'ACTIVE'=>'Y', 'SECTION_ID'=>$arSection["ID"]);
            $db_list = CIBlockSection::GetList(Array($by=>$order), $arFiltere, true);
            ?>
            <? App::getInstance()->assert($db_list->SelectedRowsCount()!=0, 'legacy') ?>
        <? else: ?>

            <? // section page ?>
            <? include Util::joinPath([$_SERVER['DOCUMENT_ROOT'], v::template('partials/catalog/section.php')]) ?>

        <? endif ?>


    <?endif?>



<?}?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>