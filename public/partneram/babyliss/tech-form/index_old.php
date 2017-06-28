<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Документация");

$rsUser = CUser::GetByID($USER->GetID());
$arUser = $rsUser->Fetch();

$IS_ERROR = false;

$IS_POST = intval($_REQUEST["IS_POST"]);
if ($IS_POST !== 1) $IS_POST = "";

$SC = $_REQUEST["SC"];
foreach($SC as $key=>$ob){
    $SC[$key] = $ob;
}

if (!$IS_POST){
    $SC["NAME"] = $arUser["WORK_COMPANY"];
}

$VLADELEC = $_REQUEST["VLADELEC"];
foreach($VLADELEC as $key=>$ob){
    if(!is_array($ob)) $ob = strip_tags($ob);
    if (!$ob) $IS_ERROR = true;
    $VLADELEC[$key] = $ob;
}
$IZDEL = $_REQUEST["IZDEL"];

foreach($IZDEL as $key=>$ob){
    if(!is_array($ob)) $ob = strip_tags($ob);
    if (!$ob && $key != "SVEDINIYA") $IS_ERROR = true;    
    $IZDEL[$key] = $ob; 
}

$DAN = $_REQUEST["DAN"];
foreach($DAN as $key=>$ob){
    $ob = strip_tags($ob);
    if (!$ob) $IS_ERROR = true;
    $DAN[$key] = $ob;   
} 
$IS_NULL_ZAP = false;
$ZAP = $_REQUEST["ZAP"];
foreach($ZAP as $key=>$ob) {
    foreach($ob as $k=>$zp) {
        $ZAP[$key][$k] = strip_tags($zp);
    }
}
if (!$ZAP[0]["NAME"] || !$ZAP[0]["ART"]){
    if ($IS_POST){
        $IS_NULL_ZAP = true;
        $IS_ERROR = true;
    } 
}
$ZAP_COUNT = count($ZAP);
/*$PRICHINA = strip_tags($_REQUEST["PRICHINA"]);

if (!$PRICHINA) $IS_ERROR = true;*/

if (!$IZDEL["KOMPLEKT"]) $IS_ERROR = true;

/*if (!$DAN["DEFEKT"]) $IS_ERROR = true;
if ($DAN["DEFEKT"] == 3 && !$DAN["DEFEKT3_DESCR"]) $IS_ERROR = true;*/


if (!$IS_ERROR && $IS_POST){
    require_once $_SERVER["DOCUMENT_ROOT"].'/bitrix/templates/main_page/include/phpexcel/PHPExcel/IOFactory.php';
    $arPropProduct = CIBlockPropertyEnum::GetByID($_REQUEST["IZDEL"]["NAME"]);
    $IZDEL["NAME"] = $arPropProduct["VALUE"];
    $book = PHPExcel_IOFactory::load("zakl.xls");
    
    $book->getActiveSheet()->setCellValue('D4', $SC["NAME"]); 
    $book->getActiveSheet()->setCellValue('D5', $SC["DATA_ZAKL"]); 
    $book->getActiveSheet()->setCellValue('D6', $SC["ADRES"]); 
    $book->getActiveSheet()->setCellValue('D7', $SC["PHONE"]); 

    $book->getActiveSheet()->setCellValue('D11', $VLADELEC["FIO"]); 
    $book->getActiveSheet()->setCellValue('D12', $VLADELEC["PHONE"]); 
    $book->getActiveSheet()->setCellValue('D13', $VLADELEC["ADRES"]); 

    $book->getActiveSheet()->setCellValue('D17', $IZDEL["NAME"]); 
    $book->getActiveSheet()->setCellValue('D18', $IZDEL["FULL_NAME"]); 
    $book->getActiveSheet()->setCellValue('D19', $IZDEL["TYPE"]); 
    $book->getActiveSheet()->setCellValue('D20', $IZDEL["DATA_PROIZV"]); 
    $book->getActiveSheet()->setCellValue('D21', $IZDEL["DATA_PRODAJI"]); 
    
    foreach ($IZDEL["KOMPLEKT"] as $key=>$propID):
        $arComplect = CIBlockPropertyEnum::GetByID($propID);
        if($key > 0) $KOMPLEKT .= ", ";
        $KOMPLEKT .= $arComplect["VALUE"];
    endforeach;
    
    $book->getActiveSheet()->setCellValue('D22', $KOMPLEKT);
    $book->getActiveSheet()->setCellValue('D23', $IZDEL["DATA_POSTUP"]); 
    $book->getActiveSheet()->setCellValue('D24', $IZDEL["PRIZNAKI_NEISPR"]); 
    $book->getActiveSheet()->setCellValue('D25', $IZDEL["SVEDINIYA"]);
    
    $book->getActiveSheet()->setCellValue('D29', $DAN["VIEV_DEFEKT"]); 
    /*switch($DAN["DEFEKT"]){
        case 1:
            $DEFEKT = "заводской дефект";
        break;
        case 2:
            $DEFEKT = "механические повреждения";
        break;
        case 3:
            $DEFEKT = "нарушение правил эксплуатации";
            $DEFEKT.= " - ".$DAN["DEFEKT3_DESCR"];
        break;
    }*/
    $book->getActiveSheet()->setCellValue('D30', $DEFEKT);
    
    $start_pos = 38;
    foreach($ZAP as $key=>$ob) {
        if($ob["NAME"] && $ob["ART"]){
            $book->getActiveSheet()->setCellValue('A'.$start_pos, $ob["NAME"]); 
            $book->getActiveSheet()->setCellValue('E'.$start_pos, $ob["ART"]); 
            if ($ob["SKLAD"]) $ob["SKLAD"] = "да";
            else $ob["SKLAD"] = "нет";
            
            $book->getActiveSheet()->setCellValue('F'.$start_pos, $ob["SKLAD"]);
            $start_pos++;
        }
    }
    
    /*switch($PRICHINA){
        case 1:
            $TPRICHINA = "распоряжение фирмы-изготовителя";
        break;
        case 2:
            $TPRICHINA = "отказ владельца от ремонта в соответствии с «Законом о защите прав потребителей»";
        break;
        case 3:
            $TPRICHINA = "отсутствие возможности получения необходимой замены изделия";
        break;
    }
    $book->getActiveSheet()->setCellValue('D34', $TPRICHINA);*/
    $objWriter = PHPExcel_IOFactory::createWriter($book, 'Excel5'); 
    
    CModule::IncludeModule("iblock");
    $el = new CIBlockElement;
    $num = 1;
    $arSelect = Array("ID", "NAME", "PROPERTY_NUMER");
    $arFilter = Array("IBLOCK_ID"=>10, "ACTIVE"=>"Y");
    $res = CIBlockElement::GetList(Array("ID"=>"DESC"), $arFilter, false, Array("nTopCount"=>1), $arSelect);
    if($ob = $res->Fetch()){
        $num = $ob["PROPERTY_NUMER_VALUE"];
        $num = explode("/", $num);
        $num = $num[1]+1;
    }
    
    $objWriter->save("teh_zaklyuchenie_".date("y")."_".$num.".xls");
    
    $PROP = array();
    $PROP[49] = date("y")."/".$num;  
    $PROP[50] = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/partneram/babyliss/tech-form/teh_zaklyuchenie_".date("y")."_".$num.".xls");
    
    $PROP[53] = $USER->GetID();
    $PROP[54] = $arUser["WORK_COMPANY"];

    $imgs = array();
    $is_image = CFile::IsImage($_FILES["img1"]["name"], $_FILES["img1"]["type"]);
    if ($is_image){
        $img1 = CFile::SaveFile($_FILES["img1"], "tech-zakl-imgs");
        $img1 = CFile::MakeFileArray($img1);
        $imgs[] = $img1;
    } 

    $is_image = CFile::IsImage($_FILES["img2"]["name"], $_FILES["img2"]["type"]);
    if ($is_image){
        $img2 = CFile::SaveFile($_FILES["img2"], "tech-zakl-imgs");
        $img2 = CFile::MakeFileArray($img2);
        $imgs[] = $img2;
    } 

    $is_image = CFile::IsImage($_FILES["img3"]["name"], $_FILES["img3"]["type"]);
    if ($is_image){
        $img3 = CFile::SaveFile($_FILES["img3"], "tech-zakl-imgs");
        $img3 = CFile::MakeFileArray($img3);
        $imgs[] = $img3;
    }   

    $PROP[55] = $imgs;
    $PROP["PRODUCTS"] = $_POST["IZDEL"]["NAME"];
    $PROP["COMPLECT"] = $_POST["IZDEL"]["KOMPLEKT"];
    $PROP["STATUS"] = Array("VALUE" => 59);
    $data = date("d.m.Y");
    $arLoadProductArray = Array(
        "IBLOCK_ID"      => 10,
        "PROPERTY_VALUES"=> $PROP,
        "NAME"           => "Техническое заключение ".date("y")."/".$num,
        "ACTIVE"         => "Y",
        "DATE_ACTIVE_FROM" => $data
    );

    if($ID = $el->Add($arLoadProductArray)){
        $OK = true;
        $arEventFields = array(
            "NOMER"             => date("y")."/".$num,
            "DATA_OTPR"         => $data,
            "URL"               => "http://".SITE_SERVER_NAME."/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=10&type=region&ID=".$ID."&lang=ru&find_section_section=0&WF=Y"
        );
 
        $arSelect = Array("ID", "NAME", "PROPERTY_FORMA");
        $arFilter = Array("IBLOCK_ID"=>10, "ACTIVE"=>"Y", "ID"=>$ID);
        $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nTopCount"=>1), $arSelect);
        if($ob = $res->Fetch()){
            $fid = $ob["PROPERTY_FORMA_VALUE"];
        }
    
        CEvent::Send("TEH_ZAKL", "s1", $arEventFields, "N", "", array($fid));
    }
    unlink($_SERVER["DOCUMENT_ROOT"]."/partneram/babyliss/tech-form/teh_zaklyuchenie_".date("y")."_".$num.".xls");

    unset($_FILES);
    unset($SC);
    unset($VLADELEC);
    unset($IZDEL);
    unset($DAN);
    unset($ZAP);
    unset($PRICHINA);
    unset($IS_POST);
    unset($ZAP_COUNT);

}

global $filter;
$filter = array("PROPERTY_USER"=>$USER->GetID());
?>
<?$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "forms",
    Array(
        "DISPLAY_DATE" => "N",
        "DISPLAY_NAME" => "N",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "N",
        "AJAX_MODE" => "N",
        "IBLOCK_TYPE" => "region",
        "IBLOCK_ID" => "10",
        "NEWS_COUNT" => "90",
        "SORT_BY1" => "ID",
        "SORT_ORDER1" => "ASC",
        "SORT_BY2" => "",
        "SORT_ORDER2" => "",
        "FILTER_NAME" => "filter",
        "FIELD_CODE" => Array(""),
        "PROPERTY_CODE" => Array("NUMER", "FORMA", "STATUS", "USER_NAME", "USER_IMGS"),
        "CHECK_DATES" => "N",
        "DETAIL_URL" => "",
        "PREVIEW_TRUNCATE_LEN" => "",
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "SET_TITLE" => "N",
        "SET_STATUS_404" => "N",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "ADD_SECTIONS_CHAIN" => "N",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "INCLUDE_SUBSECTIONS" => "N",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600",
        "CACHE_FILTER" => "Y",
        "CACHE_GROUPS" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "PAGER_TITLE" => "",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => "",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "N",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_ADDITIONAL" => ""
    )
);?>

<?if($OK):?>
    <h1 style="margin:20px; text-align: center">ТЗ отправлено. Сервисный провайдер ответит вам в ближайшее время</h1>
<?endif?>
<p><a href="#" style="text-transform: uppercase; color: #737373;" id="show-form" class="print-it">Заполнить техническое заключение</a></p>

<script>
$(function(){
    $('#show-form').click(function(){
        $('#form-tech').css("display","block");
        return false;
    });
    
    $('input[name="SC[PHONE]"]').inputmask('+7 (999) 999-99-99', {
        'placeholder': "+7 (___) ___-__-__"
    });
    
    $('input[name="VLADELEC[PHONE]"]').inputmask('+7 (999) 999-99-99', {
        'placeholder': "+7 (___) ___-__-__"
    });

    $('input[name="SC[DATA_ZAKL]"]').inputmask('99.99.2099', {
        'placeholder': "__.__.20__"
    });
    $('input[name="IZDEL[DATA_PRODAJI]"]').inputmask('99.99.2099', {
        'placeholder': "__.__.20__"
    });     
    
    $('input[name="IZDEL[DATA_POSTUP]"]').inputmask('99.99.2099', {
        'placeholder': "__.__.20__"
    });     
    
    var count = $("#ZAP_COUNT").val()-0;
    $("#add-zap").click(function(){
        $("#zap-body").append('<tr>'+ 
            '<td><input type="text" class="input-form" name="ZAP['+count+'][NAME]" value="" /></td>'+
            '<td><input type="text" class="input-form" name="ZAP['+count+'][ART]" value="" /></td>'+
            '<td>'+
                '<ul class="input-list">'+
                    '<li><label for="DETAL_RAD'+(count+3)+'">есть</label> <input id="DETAL_RAD'+(count+3)+'" type="radio" name="ZAP['+count+'][SKLAD]" value="1"></li>'+
                    '<li><label for="DETAL_RAD'+(count+4)+'">нет</label> <input  id="DETAL_RAD'+(count+4)+'" type="radio" name="ZAP['+count+'][SKLAD]" value="0"></li>'+
                '</ul>'+
            '</td>'+
        '</tr>');
        count++;
        return false;
    });


    
    $("#DEFEKT1").click(function(){
        $("#DEFEKT3-DESCR").prop('disabled', true);
    });
    
    $("#DEFEKT2").click(function(){
        $("#DEFEKT3-DESCR").prop('disabled', true);
    });
    
    $("#DEFEKT3").click(function(){
        $("#DEFEKT3-DESCR").prop('disabled', false);
    }); 
});
</script>

<form <?if (!$IS_POST) echo 'style="display:none;"';?> id="form-tech" enctype="multipart/form-data" action="" method="post">
<input type="hidden" name="IS_POST" value="1" />
<h2 class="input-h2">Техническое заключение на изделие BABYLISS</h2>
    <table class="input-table">
        <tr>
            <td class="input-td <?if ($IS_POST && !$SC["NAME"]) echo 'errot-field';?>"><span class="filed-title">Выдано сервисным центром:</span></td>
            <td><input type="text" class="input-form" name="SC[NAME]" value="<?=htmlspecialchars($SC["NAME"])?>" placeholder="название СЦ" /></td>
        </tr>
        <tr>
            <td class="input-td <?if ($IS_POST && !$SC["DATA_ZAKL"]) echo 'errot-field';?>"><span class="filed-title">Дата выдачи заключения:</span></td>
            <td><input type="text" class="input-form" placeholder="01.01.20<?=date("y")?>" name="SC[DATA_ZAKL]" value="<?=$SC["DATA_ZAKL"] ? htmlspecialchars($SC["DATA_ZAKL"]) : date("d.m.Y")?>" /></td>
        </tr>
        <tr>
            <td class="input-td <?if ($IS_POST && !$SC["ADRES"]) echo 'errot-field';?>"><span class="filed-title">Адрес сервисного центра:</span></td>
            <?if($arUser["PERSONAL_STATE"]) $address = $arUser["PERSONAL_STATE"];
            if($arUser["PERSONAL_ZIP"]):
                if(strlen($address)) $address .= ", ";
                $address .= $arUser["PERSONAL_ZIP"];
            endif; 
            if($arUser["PERSONAL_CITY"]):
                if(strlen($address)) $address .= ", ";
                $address .= $arUser["PERSONAL_CITY"];
            endif;
            if($arUser["PERSONAL_STREET"]):
                if(strlen($address)) $address .= ", ";
                $address .= $arUser["PERSONAL_STREET"];
            endif;?>
            <td>
                <textarea class="input-form" name="SC[ADRES]"><?=$SC["ADRES"] ? htmlspecialchars($SC["ADRES"]) : $address?></textarea>
            </td>
        </tr>
        <tr>
            <td class="input-td <?if ($IS_POST && !$SC["PHONE"]) echo 'errot-field';?>"><span class="filed-title">Тел.СЦ:</span></td>
            <td><input class="input-form" type="text" name="SC[PHONE]" value="<?=$SC["PHONE"] ? htmlspecialchars($SC["PHONE"]) : $arUser["PERSONAL_PHONE"]?>" /></td>
        </tr>
    </table>

    <h3 class="input-h3">I. Данные о владельце изделия</h3>
    <table class="input-table">
        <tr>
            <td class="input-td <?if ($IS_POST && !$VLADELEC["FIO"]) echo 'errot-field';?>"><span class="filed-title">ФИО:</span></td>
            <td><input type="text" class="input-form" name="VLADELEC[FIO]" value="<?=htmlspecialchars($VLADELEC["FIO"])?>" /></td>
        </tr>
        <tr>
            <td class="input-td <?if ($IS_POST && !$VLADELEC["PHONE"]) echo 'errot-field';?>"><span class="filed-title">Контактный телефон:</span></td>
            <td><input type="text" class="input-form" name="VLADELEC[PHONE]" value="<?=htmlspecialchars($VLADELEC["PHONE"])?>" /></td>
        </tr>
        <tr>
            <td class="input-td <?if ($IS_POST && !$VLADELEC["ADRES"]) echo 'errot-field';?>"><span class="filed-title">Адрес:</span></td>
            <td><textarea class="input-form" name="VLADELEC[ADRES]"><?=htmlspecialchars($VLADELEC["ADRES"])?></textarea></td>
        </tr>
    </table>

    <h3 class="input-h3">II. Данные об изделии</h3>
    <table class="input-table">
        <tr>
            <td class="input-td <?if ($IS_POST && !$IZDEL["NAME"]) echo 'errot-field';?>"><span class="filed-title">Наименование:</span></td>
            <td>
                <?$arProducts = CIBlockPropertyEnum::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>10, "CODE"=>"PRODUCTS"));?>
                <select class="input-form" name="IZDEL[NAME]">
                    <?while($arProduct = $arProducts->GetNext()):?>
                        <option value="<?=$arProduct["ID"]?>"<?if($IZDEL["NAME"] == $arProduct["VALUE"]):?> selected=""<?endif?>><?=$arProduct["VALUE"]?></option>
                    <?endwhile?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="input-td <?if ($IS_POST && !$IZDEL["FULL_NAME"]) echo 'errot-field';?>"><span class="filed-title">Полное торговое название:</span></td>
            <td><input type="text" class="input-form" name="IZDEL[FULL_NAME]" value="<?=htmlspecialchars($IZDEL["FULL_NAME"])?>" /></td>
        </tr>
        <tr>
            <td class="input-td <?if ($IS_POST && !$IZDEL["TYPE"]) echo 'errot-field';?>"><span class="filed-title">Тип:</span></td>
            <td><input type="text" class="input-form" name="IZDEL[TYPE]" value="<?=htmlspecialchars($IZDEL["TYPE"])?>" /></td>
        </tr>
        <tr>
            <td class="input-td <?if ($IS_POST && !$IZDEL["DATA_PROIZV"]) echo 'errot-field';?>"><span class="filed-title">Дата производства:</span></td>
            <td><input maxlength="10" type="text" class="input-form" name="IZDEL[DATA_PROIZV]" value="<?=htmlspecialchars($IZDEL["DATA_PROIZV"])?>" /></td>
        </tr>
        <tr>
            <td class="input-td <?if ($IS_POST && !$IZDEL["DATA_PRODAJI"]) echo 'errot-field';?>"><span class="filed-title">Дата продажи:</span></td>
            <td><input type="text" class="input-form" placeholder="01.01.20<?=date("y")?>"  name="IZDEL[DATA_PRODAJI]" value="<?=htmlspecialchars($IZDEL["DATA_PRODAJI"])?>" /></td>
        </tr>
        <tr>
            <td class="input-td <?if ($IS_POST && !$IZDEL["KOMPLEKT"]) echo 'errot-field';?>"><span class="filed-title">Комплектность:</span></td>
            <td>
                <ul class="input-list">
                    <?$arComplects = CIBlockPropertyEnum::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>10, "CODE"=>"COMPLECT"));
                    while($arComplect = $arComplects->GetNext()):?>
                        <li>
                            <label><?=$arComplect["VALUE"]?>
                                <input <?if (in_array($arComplect["ID"], $IZDEL["KOMPLEKT"])) echo "checked";?> type="checkbox" name="IZDEL[KOMPLEKT][]" value="<?=$arComplect["ID"]?>">
                            </label>
                        </li>
                    <?endwhile?>
                </ul>
            </td>
        </tr>
        <tr>
            <td class="input-td <?if ($IS_POST && !$IZDEL["DATA_POSTUP"]) echo 'errot-field';?>"><span style="padding-right: 0;" class="filed-title">Дата поступления в сервисный центр:</span></td>
            <td><input type="text" class="input-form" placeholder="01.01.20<?=date("y")?>"  name="IZDEL[DATA_POSTUP]" value="<?=$IZDEL["DATA_POSTUP"] ? htmlspecialchars($IZDEL["DATA_POSTUP"]) : date("d.m.Y") ?>" /></td>
        </tr>
        <tr>
            <td class="input-td <?if ($IS_POST && !$IZDEL["PRIZNAKI_NEISPR"]) echo 'errot-field';?>"><span style="padding-right: 0;" class="filed-title">Внешние признаки неисправности:</span></td>
            <td><textarea class="input-form" name="IZDEL[PRIZNAKI_NEISPR]"><?=htmlspecialchars($IZDEL["PRIZNAKI_NEISPR"])?></textarea></td>
        </tr>
        <tr>
            <td class="input-td"><span style="padding-right: 0;" class="filed-title">Сведения о предыдущих ремонтах:</span></td>
            <td><textarea class="input-form" name="IZDEL[SVEDINIYA]" placeholder="№ гарантийной квитанции, даты приема и выдачи изделия"><?=htmlspecialchars($IZDEL["SVEDINIYA"])?></textarea></td>
        </tr>
    </table>

    <h3 class="input-h3">III. Данные освидетельствования</h3>
    <table class="input-table">
        <tr>
            <td class="input-td <?if ($IS_POST && !$DAN["VIEV_DEFEKT"]) echo 'errot-field';?>"><span class="filed-title">Выявленный дефект:</span></td>
            <td><textarea class="input-form" name="DAN[VIEV_DEFEKT]"><?=htmlspecialchars($DAN["VIEV_DEFEKT"])?></textarea></td>
        </tr>
        <tr>
            <td class="input-td" style="vertical-align:top;"><span style="padding-right: 0;" class="filed-title">Заключение о причинах неисправности:</span></td>
            <td>
                <ul class="input-list defekt">
                    <li><label for="DEFEKT1">заводской дефект</label> <input <?if ($DAN["DEFEKT"] == 1) echo "checked";?> id="DEFEKT1" type="radio" name="DAN[DEFEKT]" value="1"></li>
                    <li><label for="DEFEKT2">механические повреждения</label> <input <?if ($DAN["DEFEKT"] == 2) echo "checked";?> type="radio" id="DEFEKT2" name="DAN[DEFEKT]" value="2"></li>
                    <li>
                        <label for="DEFEKT3">нарушение правил эксплуатации</label> <input <?if ($DAN["DEFEKT"] == 3) echo "checked";?> id="DEFEKT3" type="radio" name="DAN[DEFEKT]" value="3">
                        <br>
                        <textarea <?if ($DAN["DEFEKT"] != 3 && !$DAN["DEFEKT3_DESCR"]) echo "disabled";?> style="margin-top: 5px;" id="DEFEKT3-DESCR" class="input-form" name="DAN[DEFEKT3_DESCR]" placeholder=""><?if ($IS_POST && $DAN["DEFEKT"] == 3 && $DAN["DEFEKT3_DESCR"]) echo $DAN["DEFEKT3_DESCR"];?></textarea>
                    </li>
                </ul>
            </td>
        </tr>
    </table>
    
    <span class="filed-title <?if ($IS_NULL_ZAP) echo 'errot-field';?>">Запчасти, необходимые для восстановления:</span><br>
    <input type="hidden" id="ZAP_COUNT" name="ZAP_COUNT" value="<?=($ZAP_COUNT)?$ZAP_COUNT:3?>" />
    <table style="margin-top:10px;" class="input-table">
        <thead>
            <tr>
                <th>название запчасти</th>
                <th>артикул</th>
                <th>наличие на складе</th>
            </tr>
        </thead>
        <tbody id="zap-body">
            <tr>
                <td><input placeholder="Не заполнять!" type="text" class="input-form" name="ZAP[0][NAME]" value="<?=htmlspecialchars($ZAP[0]["NAME"])?>" /></td>
                <td><input placeholder="Не заполнять!" type="text" class="input-form" name="ZAP[0][ART]" value="<?=htmlspecialchars($ZAP[0]["ART"])?>" /></td>
                <td>
                    <ul class="input-list">
                        <li><label for="DETAL_RAD1">есть</label> <input id="DETAL_RAD1" <?if ($ZAP[0]["SKLAD"] == 1) echo "checked";?> type="radio" name="ZAP[0][SKLAD]" value="1"></li><li><label for="DETAL_RAD2">нет</label> <input id="DETAL_RAD2" <?if ($ZAP[0]["SKLAD"] == 0) echo "checked";?> type="radio" name="ZAP[0][SKLAD]" value="0"></li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td><input placeholder="Не заполнять!" type="text" class="input-form" name="ZAP[1][NAME]" value="<?=htmlspecialchars($ZAP[1]["NAME"])?>" /></td>
                <td><input placeholder="Не заполнять!" type="text" class="input-form" name="ZAP[1][ART]" value="<?=htmlspecialchars($ZAP[1]["ART"])?>" /></td>
                <td>
                    <ul class="input-list">
                        <li><label for="DETAL_RAD3">есть</label> <input id="DETAL_RAD3" <?if ($ZAP[1]["SKLAD"] == 1) echo "checked";?> type="radio" name="ZAP[1][SKLAD]" value="1"></li><li><label for="DETAL_RAD4">нет</label> <input  id="DETAL_RAD4" <?if ($ZAP[1]["SKLAD"] == 0) echo "checked";?> type="radio" name="ZAP[1][SKLAD]" value="0"></li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td><input placeholder="Не заполнять!" type="text" class="input-form" name="ZAP[2][NAME]" value="<?=htmlspecialchars($ZAP[2]["NAME"])?>" /></td>
                <td><input placeholder="Не заполнять!" type="text" class="input-form" name="ZAP[2][ART]" value="<?=htmlspecialchars($ZAP[2]["ART"])?>" /></td>
                <td>
                    <ul class="input-list">
                        <li><label for="DETAL_RAD5">есть</label> <input id="DETAL_RAD5" <?if ($ZAP[2]["SKLAD"] == 1) echo "checked";?> type="radio" name="ZAP[2][SKLAD]" value="1"></li><li><label for="DETAL_RAD6">нет</label> <input  id="DETAL_RAD6" <?if ($ZAP[2]["SKLAD"] == 0) echo "checked";?> type="radio" name="ZAP[2][SKLAD]" value="0"></li>
                    </ul>
                </td>
            </tr>
            <?if ($ZAP_COUNT>2){
                foreach($ZAP as $key=>$ob){
                    if ($key > 2){?>
                        <tr>
                            <td><input type="text" class="input-form" name="ZAP[<?=$key?>][NAME]" value="<?=htmlspecialchars($ob["NAME"])?>" /></td>
                            <td><input type="text" class="input-form" name="ZAP[<?=$key?>][ART]" value="<?=htmlspecialchars($ob["ART"])?>" /></td>
                            <td>
                                <ul class="input-list">
                                    <li><label for="DETAL_RAD<?=($key+3)?>">есть</label> <input id="DETAL_RAD<?=($key+3)?>" <?if ($ob["SKLAD"] == 1) echo "checked";?> type="radio" name="ZAP[<?=$key?>][SKLAD]" value="1"></li><li><label for="DETAL_RAD<?=($key+4)?>">нет</label> <input  id="DETAL_RAD<?=($key+4)?>" <?if ($ob["SKLAD"] == 0) echo "checked";?> type="radio" name="ZAP[<?=$key?>][SKLAD]" value="0"></li>
                                </ul>
                            </td>
                        </tr>                       
                    <?}?>
                <?}
            }
            ?>
        </tbody>
    </table>
    <!--<p><a href="#" id="add-zap" style="text-transform: uppercase; color: #737373;" class="print-it">Добавить запчасть</a></p>-->

    <h3 class="input-h3">IV.Причина невозможности ремонта</h3>
    <table class="input-table">
        <tr>
            <td>
                <ol class="prichina">
                    <li><label for="PRICHINA1">распоряжение фирмы-изготовителя</label> <input <?if ($PRICHINA == 1) echo "checked";?> id="PRICHINA1" type="radio" name="PRICHINA" value="1"></li>
                    <li><label for="PRICHINA2">отказ владельца от ремонта в соответствии с «Законом о защите прав потребителей»</label> <input <?if ($PRICHINA == 2) echo "checked";?> type="radio" id="PRICHINA2" name="PRICHINA" value="2"></li>
                    <li><label for="PRICHINA3">отсутствие возможности получения необходимой замены изделия</label> <input <?if ($PRICHINA == 3) echo "checked";?> type="radio" id="PRICHINA3" name="PRICHINA" value="3"></li>
                </ol>
            </td>
        </tr>
    </table>
    <h3 class="input-h3">Прикрепить скриншоты</h3>
    <div class="input-file"><input type="file" name="img1" accept="image/*" /></div>
    <div class="input-file"><input type="file" name="img2" accept="image/*" /></div>
    <div class="input-file"><input type="file" name="img3" accept="image/*" /></div>
    <br><br>
    <p style="font-weight:bold;">Все поля обязательны для заполнения.</p>
    <p style="font-weight:bold;">Поля которые вы не заполнили будут выделены <span style="color:red">красным цветом</span>.</p>
    <p><input class="print-it" type="submit" value="ОТПРАВИТЬ ЗАКЛЮЧЕНИЕ" /></p>
</form>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>