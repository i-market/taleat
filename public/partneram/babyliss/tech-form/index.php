<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Техническое заключение");

use App\View as v;

LocalRedirect(v::path('partneram/reports'));

$arUser = CUser::GetByID($USER->GetID())->GetNext();
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
$NO_IMG = false;
if (!(($_FILES['img1']['error'] == 0) || ($_FILES['img2']['error'] == 0) || ($_FILES['img3']['error'] == 0))){
	$IS_ERROR = true;
	$NO_IMG = true;
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
    if (!$ob && $key != "SVEDINIYA" && $key != "DATA_PRODAJI") $IS_ERROR = true;
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
/*if (!$ZAP[0]["NAME"] || !$ZAP[0]["ART"]){
    if ($IS_POST){
        $IS_NULL_ZAP = true;
        $IS_ERROR = true;
    }
}*/
$ZAP_COUNT = count($ZAP);
$PRICHINA = strip_tags($_REQUEST["PRICHINA"]);
$ITEM_PLACE = strip_tags($_REQUEST["ITEM_PLACE"]);
if (!$ITEM_PLACE) $IS_ERROR = true;
//if (!$PRICHINA) $IS_ERROR = true;

if (!$IZDEL["KOMPLEKT"]) $IS_ERROR = true;

if (!$DAN["DEFEKT"]) $IS_ERROR = true;
if ($DAN["DEFEKT"] == 64 && !$DAN["DEFEKT3_DESCR"]) $IS_ERROR = true;



if (!$IS_ERROR && $IS_POST){
    require_once $_SERVER["DOCUMENT_ROOT"].'/bitrix/templates/main_page/include/phpexcel/PHPExcel/IOFactory.php';
	$arProduct = CIBlockElement::GetByID($_REQUEST["IZDEL"]["NAME"]);
	$product = $arProduct->GetNext()["NAME"];

	$arPropModel = CIBlockPropertyEnum::GetByID($_REQUEST["IZDEL"]["MODEL"]);
	$model = $arPropModel["VALUE"];

    $book = PHPExcel_IOFactory::load("zakl_new1.xls");

	$date = date("Y")."-01-01";
	$filter = array(
		"IBLOCK_ID"=>10,
		"ACTIVE"=>"Y",
		">=DATE_ACTIVE_FROM" => ConvertTimeStamp(strtotime($date),"FULL"),
	);
	$num = 1;
	$res = CIBlockElement::GetList(Array("ID"=>"DESC"), $filter, false, Array("nTopCount"=>1), Array("ID", "PROPERTY_NUMER"));
    if($ob = $res->Fetch()){
        $num = $ob["PROPERTY_NUMER_VALUE"];
        $num = explode("/", $num);
        $num = $num[1]+1;
        for ($x = strlen($num); $x < 6; $x++):
            $num = "0".$num;
        endfor;
    };

    $book->getActiveSheet()->setCellValue('A1', "Техническое заключение на изделие BABYLISS №".date("y")."/".$num);
    $book->getActiveSheet()->setCellValue('A4', $SC["NAME"]);
    $book->getActiveSheet()->setCellValue('F3', $SC["DATA_ZAKL"]);
    $book->getActiveSheet()->setCellValue('A8', $SC["ADRES"]);
    $book->getActiveSheet()->setCellValue('D7', $SC["PHONE"]);

    $book->getActiveSheet()->setCellValue('B10', $VLADELEC["FIO"]);
    $book->getActiveSheet()->setCellValue('H10', $VLADELEC["PHONE"]);
    $book->getActiveSheet()->setCellValue('A11', $VLADELEC["ADRES"]);

    $book->getActiveSheet()->setCellValue('C13', $product);
    $book->getActiveSheet()->setCellValue('C14', $model);
    $book->getActiveSheet()->setCellValue('H12', $IZDEL["TYPE"]);
    $book->getActiveSheet()->setCellValue('I14', $IZDEL["DATA_PROIZV"]);
    $book->getActiveSheet()->setCellValue('C15', $IZDEL["DATA_PRODAJI"]);

    foreach ($IZDEL["KOMPLEKT"] as $key=>$propID):
        $arComplect = CIBlockPropertyEnum::GetByID($propID);
        if($key > 0) $KOMPLEKT .= ", ";
        $KOMPLEKT .= $arComplect["VALUE"];
    endforeach;

    $book->getActiveSheet()->setCellValue('C16', $KOMPLEKT);
    $book->getActiveSheet()->setCellValue('E17', $IZDEL["DATA_POSTUP"]);
    $book->getActiveSheet()->setCellValue('A19', $IZDEL["PRIZNAKI_NEISPR"]);
    $book->getActiveSheet()->setCellValue('E20', $IZDEL["SVEDINIYA"]);

    $book->getActiveSheet()->setCellValue('D23', $DAN["VIEV_DEFEKT"]);
    switch($DAN["DEFEKT"]){
        case 62:
            $DEFEKT = "Заводской дефект";
        break;
        case 63:
            $DEFEKT = "Механические повреждения";
        break;
        case 64:
            $DEFEKT = "Нарушение правил эксплуатации";
            $book->getActiveSheet()->setCellValue('A25', $DAN["DEFEKT3_DESCR"]);
        break;
    }

    $book->getActiveSheet()->setCellValue('F24', $DEFEKT);

    $start_pos = 29;
    foreach($ZAP as $key=>$ob) {
        if($key > 2) break;
        if($ob["NAME"] && $ob["ART"]){
            $book->getActiveSheet()->setCellValue('A'.$start_pos, $ob["NAME"]);
            $book->getActiveSheet()->setCellValue('E'.$start_pos, $ob["ART"]);
            if ($ob["SKLAD"]) $ob["SKLAD"] = "Да";
            else $ob["SKLAD"] = "Нет";

            $book->getActiveSheet()->setCellValue('I'.$start_pos, $ob["SKLAD"]);
            $start_pos++;
        }
    }

    switch($PRICHINA){
        case 65:
            $TPRICHINA = "Распоряжение фирмы-изготовителя";
        break;
        case 66:
            $TPRICHINA = "Отказ владельца от ремонта в соответствии с «Законом о защите прав потребителей»";
        break;
        case 67:
            $TPRICHINA = "Отсутствие возможности получения необходимой замены изделия";
        break;
    }
    $book->getActiveSheet()->setCellValue('A34', $TPRICHINA);
    switch($ITEM_PLACE):
        case 68:
            $book->getActiveSheet()->setCellValue('A36', "Выдано на руки владельцу");
        break;

        case 69:
            $book->getActiveSheet()->setCellValue('A36', "Оставлено в сервисном центре на ответственное хранение");
        break;

        default: break;
    endswitch;

    $objWriter = PHPExcel_IOFactory::createWriter($book, 'Excel5');

    CModule::IncludeModule("iblock");
    $el = new CIBlockElement;

    $objWriter->save("new_tz_".date("y")."_".$num.".xls");

    $PROP = array();
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
    $PROP["USER_IMGS"] = $imgs;
    $PROP["NUMER"] = date("y")."/".$num;
    $PROP["FORMA"] = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/partneram/babyliss/tech-form/new_tz_".date("y")."_".$num.".xls");

    $PROP["USER"] = $USER->GetID();
    $PROP["SC_NAME"] = htmlspecialcharsBack($SC["NAME"]);
    $PROP["PRODUCT"] = $product;
    $PROP["ITEM_COMPLECT"] = $_POST["IZDEL"]["KOMPLEKT"];
    $PROP["STATUS"] = Array("VALUE" => 59);
    $PROP["DATE_REPORT"] = $SC["DATA_ZAKL"];
    $PROP["SC_PHONE"] = $SC["PHONE"];
    $PROP["SC_ADDRESS"] = $SC["ADRES"];
    $PROP["OWNER_TITLE"] = $VLADELEC["FIO"];
    $PROP["OWNER_PHONE"] = $VLADELEC["PHONE"];
    $PROP["OWNER_ADDRESS"] = $VLADELEC["ADRES"];
    $PROP["MODEL"] = $model;
    $PROP["ITEM_TYPE"] = $IZDEL["TYPE"];
    $PROP["ITEM_CREATED"] = $IZDEL["DATA_PROIZV"];
    $PROP["ITEM_DATE_SALE"] = $IZDEL["DATA_PRODAJI"];
    $PROP["ITEM_DATE_GET"] = $IZDEL["DATA_POSTUP"];
    $PROP["ITEM_FAULT"] = $IZDEL["PRIZNAKI_NEISPR"];
    $PROP["ITEM_REPAIRS"] = $IZDEL["SVEDINIYA"];
    $PROP["ITEM_REAL_FAULT"] = $DAN["VIEV_DEFEKT"];
    $PROP["REPORT_FAULT"] = $DAN["DEFEKT"];
    if($DAN["DEFEKT"] == 64) $PROP["USER_FAULT"] = $DAN["DEFEKT3_DESCR"];
    else $PROP["USER_FAULT"] = "";
    $PROP["REASON_FAIL_REPAIR"] = $PRICHINA;
    $PROP["ITEM_PLACE"] = $ITEM_PLACE;

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
    //unset($SC);
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
        "NEWS_COUNT" => "100",
        "SORT_BY1" => "ID",
        "SORT_ORDER1" => "ASC",
        "SORT_BY2" => "",
        "SORT_ORDER2" => "",
        "FILTER_NAME" => "filter",
        "FIELD_CODE" => Array(""),
        "PROPERTY_CODE" => Array("NUMER", "APPROVED", "FORMA", "STATUS", "SC_NAME", "USER_IMGS"),
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
        "CACHE_TYPE" => "N",
        "CACHE_TIME" => "3600",
        "CACHE_FILTER" => "Y",
        "CACHE_GROUPS" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "Y",
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

<?if ($OK):?>
	<?
	$url = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'].'?success=true'; 
	header("Location: $url"); 
	?>
<?endif;?>

<?if($_GET['success'] == 'true'):?>
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

function changeModels(){
	$('#model-select').empty();
	var product_id = $('#product-select').val();
	$.ajax({
		type: "POST",
		url: "models.php",
		data: ({
			'id': product_id
		}),
		success: function ( data ) {
		  $('#model-select').empty().html(data);
		}
	 });
};
</script>

<form <?if (!$IS_POST) echo 'style="display:none;"';?> id="form-tech" enctype="multipart/form-data" action="" method="post">
<input type="hidden" name="IS_POST" value="1" />
<h2 class="input-h2">Техническое заключение на изделие BABYLISS</h2>
    <table class="input-table">
        <tr>
            <td class="input-td <?if ($IS_POST && !$SC["NAME"]) echo 'errot-field';?>"><span class="filed-title">Выдано сервисным центром:</span></td>
            <td><input type="text" class="input-form" name="SC[NAME]" value='<?=htmlspecialcharsBack($SC["NAME"])?>' placeholder="название СЦ" /></td>
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
                $address .= $arUser["WORK_ZIP"];
            endif;
            if($arUser["PERSONAL_CITY"]):
                if(strlen($address)) $address .= ", ";
                $address .= $arUser["WORK_CITY"];
            endif;
            if($arUser["PERSONAL_STREET"]):
                if(strlen($address)) $address .= ", ";
                $address .= $arUser["WORK_STREET"];
            endif;?>
            <td>
                <textarea class="input-form" name="SC[ADRES]"><?=$SC["ADRES"] ? htmlspecialchars($SC["ADRES"]) : $address?></textarea>
            </td>
        </tr>
        <tr>
            <td class="input-td <?if ($IS_POST && !$SC["PHONE"]) echo 'errot-field';?>"><span class="filed-title">Телефон СЦ:</span></td>
            <td><input class="input-form" type="text" name="SC[PHONE]" value="<?=$SC["PHONE"] ? htmlspecialchars($SC["PHONE"]) : $arUser["WORK_PHONE"]?>" /></td>
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
				<?
				$arProducts = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>11, "ACTIVE"=>"Y"), false, false, Array("ID", "NAME"));
				?>
				<select class="input-form" name="IZDEL[NAME]" id="product-select" onChange="changeModels()">
					<?
					if ($product = $arProducts->GetNext()){
						?><option value="<?=$product["ID"]?>"<?if($IZDEL["NAME"] == $product["ID"]):?> selected=""<?endif?>><?=$product["NAME"]?></option><?
						$product_id = $product['ID'];
					}
					while ($product = $arProducts->GetNext()){
						?><option value="<?=$product["ID"]?>"<?if($IZDEL["NAME"] == $product["ID"]):?> selected=""<?$product_id = $product['ID'];?><?endif?>><?=$product["NAME"]?></option><?
					}
					?>
				</select>
                <?/*$arProducts = CIBlockPropertyEnum::GetList(Array("VALUE"=>"ASC"), Array("IBLOCK_ID"=>10, "CODE"=>"ITEM_PRODUCTS"));?>
                <select class="input-form" name="IZDEL[NAME]">
                    <?while($arProduct = $arProducts->GetNext()):?>
                        <option value="<?=$arProduct["ID"]?>"<?if($IZDEL["NAME"] == $arProduct["ID"]):?> selected=""<?$product_id = $arModel['ID'];?><?endif?>><?=$arProduct["VALUE"]?></option>
                    <?endwhile?>
                </select>*/?>
            </td>
        </tr>
        <tr>
            <td class="input-td <?if ($IS_POST && !$IZDEL["MODEL"]) echo 'errot-field';?>"><span class="filed-title">Модель:</span></td>
            <td>
				<?$arModels = CIBlockElement::GetProperty(11, $product_id, "name", "asc", array("CODE"=>"MODELS"));?>
				<select class="input-form" name="IZDEL[MODEL]" id="model-select">
					<?$arRes = Array();?>
					<?while($arModel = $arModels->GetNext()):?>
						<?$arRes[$arModel['VALUE']] = $arModel['VALUE_ENUM'];?>
					<?endwhile;?>
					<?asort($arRes);?>
					<?foreach($arRes as $val => $val_enum):?>
						<option value="<?=$val?>"<?if($IZDEL["MODEL"] == $val):?> selected=""<?endif?>><?=$val_enum?></option>
					<?endforeach?>
				</select>
                <?/*$arModels = CIBlockPropertyEnum::GetList(Array("VALUE"=>"ASC"), Array("IBLOCK_ID"=>10, "CODE"=>"ITEM_MODEL"));?>
                <select class="input-form" name="IZDEL[MODEL]">
                    <?while($arModel = $arModels->GetNext()):?>
                        <option value="<?=$arModel["ID"]?>"<?if($IZDEL["MODEL"] == $arModel["ID"]):?> selected=""<?endif?>><?=$arModel["VALUE"]?></option>
                    <?endwhile?>
                </select>
				*/?>
            </td>
            <?/*<td><input type="text" class="input-form" name="IZDEL[FULL_NAME]" value="<?=htmlspecialchars($IZDEL["FULL_NAME"])?>" /></td>*/?>
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
            <td class="input-td"><span class="filed-title">Дата продажи:</span></td>
            <td><input type="text" class="input-form" placeholder="01.01.20<?=date("y")?>"  name="IZDEL[DATA_PRODAJI]" value="<?=htmlspecialchars($IZDEL["DATA_PRODAJI"])?>" /></td>
        </tr>
        <tr>
            <td class="input-td <?if ($IS_POST && !$IZDEL["KOMPLEKT"]) echo 'errot-field';?>"><span class="filed-title">Комплектность:</span></td>
            <td>
                <ul class="input-list">
                    <?$arComplects = CIBlockPropertyEnum::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>10, "CODE"=>"ITEM_COMPLECT"));
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
            <td class="input-td<?if ($IS_POST && !$DAN["DEFEKT"] || $IS_POST && $DAN["DEFEKT"] == 3 && !$DAN["DEFEKT3_DESCR"]) echo ' errot-field';?>" style="vertical-align:top;"><span style="padding-right: 0;" class="filed-title">Заключение о причинах неисправности:</span></td>
            <td>
                <ul class="input-list defekt">
                    <li><label for="DEFEKT1">Заводской дефект</label> <input <?if ($DAN["DEFEKT"] == 62) echo "checked";?> id="DEFEKT1" type="radio" name="DAN[DEFEKT]" value="62"></li>
                    <li><label for="DEFEKT2">Механические повреждения</label> <input <?if ($DAN["DEFEKT"] == 63) echo "checked";?> type="radio" id="DEFEKT2" name="DAN[DEFEKT]" value="63"></li>
                    <li>
                        <label for="DEFEKT3">Нарушение правил эксплуатации</label> <input <?if ($DAN["DEFEKT"] == 64) echo "checked";?> id="DEFEKT3" type="radio" name="DAN[DEFEKT]" value="64">
                        <br>
                        <textarea <?if ($DAN["DEFEKT"] != 64 && !$DAN["DEFEKT3_DESCR"]) echo "disabled";?> style="margin-top: 5px;" id="DEFEKT3-DESCR" class="input-form" name="DAN[DEFEKT3_DESCR]" placeholder=""><?if ($IS_POST && $DAN["DEFEKT"] == 64 && $DAN["DEFEKT3_DESCR"]) echo $DAN["DEFEKT3_DESCR"];?></textarea>
                    </li>
                </ul>
            </td>
        </tr>
    </table>

    <span class="filed-title">Запчасти, необходимые для восстановления:</span><br>
    <input type="hidden" id="ZAP_COUNT" name="ZAP_COUNT" value="<?=($ZAP_COUNT)?$ZAP_COUNT:3?>" />
    <table style="margin-top:10px;" class="input-table">
        <thead>
            <tr>
                <th>Название запчасти</th>
                <th>Артикул</th>
                <th>Наличие на складе</th>
            </tr>
        </thead>
        <tbody id="zap-body">
            <tr>
                <td>
                    <input disabled="" placeholder="Не заполнять!" type="text" class="input-form" name="ZAP[0][NAME]" value="<?=htmlspecialchars($ZAP[0]["NAME"])?>" />
                </td>
                <td>
                    <input disabled="" placeholder="Не заполнять!" type="text" class="input-form" name="ZAP[0][ART]" value="<?=htmlspecialchars($ZAP[0]["ART"])?>" />
                </td>
                <td>
                    <ul class="input-list">
                        <li>
                            <label for="DETAL_RAD1">Да</label>
                            <input disabled="" id="DETAL_RAD1" <?if ($ZAP[0]["SKLAD"] == 1) echo "checked";?> type="radio" name="ZAP[0][SKLAD]" value="1">
                        </li>
                        <li>
                            <label for="DETAL_RAD2">Нет</label>
                            <input disabled="" id="DETAL_RAD2" <?if ($ZAP[0]["SKLAD"] == 0) echo "checked";?> type="radio" name="ZAP[0][SKLAD]" value="0">
                        </li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td>
                    <input disabled="" placeholder="Не заполнять!" type="text" class="input-form" name="ZAP[1][NAME]" value="<?=htmlspecialchars($ZAP[1]["NAME"])?>" />
                </td>
                <td>
                    <input disabled="" placeholder="Не заполнять!" type="text" class="input-form" name="ZAP[1][ART]" value="<?=htmlspecialchars($ZAP[1]["ART"])?>" />
                </td>
                <td>
                    <ul class="input-list">
                        <li>
                            <label for="DETAL_RAD3">Да</label>
                            <input disabled="" id="DETAL_RAD3" <?if ($ZAP[1]["SKLAD"] == 1) echo "checked";?> type="radio" name="ZAP[1][SKLAD]" value="1">
                        </li>
                        <li>
                            <label for="DETAL_RAD4">Нет</label>
                            <input disabled="" id="DETAL_RAD4" <?if ($ZAP[1]["SKLAD"] == 0) echo "checked";?> type="radio" name="ZAP[1][SKLAD]" value="0">
                        </li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td>
                    <input disabled="" placeholder="Не заполнять!" type="text" class="input-form" name="ZAP[2][NAME]" value="<?=htmlspecialchars($ZAP[2]["NAME"])?>" /></td>
                <td>
                    <input disabled="" placeholder="Не заполнять!" type="text" class="input-form" name="ZAP[2][ART]" value="<?=htmlspecialchars($ZAP[2]["ART"])?>" />
                </td>
                <td>
                    <ul class="input-list">
                        <li>
                            <label for="DETAL_RAD5">Да</label>
                            <input disabled="" id="DETAL_RAD5" <?if ($ZAP[2]["SKLAD"] == 1) echo "checked";?> type="radio" name="ZAP[2][SKLAD]" value="1">
                        </li>
                        <li>
                            <label for="DETAL_RAD6">Нет</label>
                            <input disabled="" id="DETAL_RAD6" <?if ($ZAP[2]["SKLAD"] == 0) echo "checked";?> type="radio" name="ZAP[2][SKLAD]" value="0">
                        </li>
                    </ul>
                </td>
            </tr>
            <?/*if ($ZAP_COUNT>2){
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
            }*/
            ?>
        </tbody>
    </table>
    <!--<p><a href="#" id="add-zap" style="text-transform: uppercase; color: #737373;" class="print-it">Добавить запчасть</a></p>-->

    <h3 class="input-h3">IV.Причина невозможности ремонта</h3>
    <table class="input-table">
        <tr>
            <td>
                <ol class="prichina">
                    <li><label for="PRICHINA1">Распоряжение фирмы-изготовителя</label> <input <?if ($PRICHINA == 65) echo "checked";?> id="PRICHINA1" type="radio" name="PRICHINA" value="65"></li>
                    <li><label for="PRICHINA2">Отказ владельца от ремонта в соответствии с «Законом о защите прав потребителей»</label> <input <?if ($PRICHINA == 66) echo "checked";?> type="radio" id="PRICHINA2" name="PRICHINA" value="66"></li>
                    <li><label for="PRICHINA3">Отсутствие возможности получения необходимой замены изделия</label> <input <?if ($PRICHINA == 67) echo "checked";?> type="radio" id="PRICHINA3" name="PRICHINA" value="67"></li>
                </ol>
            </td>
        </tr>
    </table>

    <h3 class="input-h3">V. Местонахождение изделия после технического освидетельствования:</h3>
    <table class="input-table">
        <tr>
            <td>
                <ol class="prichina">
                    <li>
                        <label>Выдано на руки владельцу
                            <input <?if ($ITEM_PLACE == 68) echo "checked";?> type="radio" name="ITEM_PLACE" value="68">
                        </label>
                    </li>
                    <li>
                        <label>Оставлено в сервисном центре на ответственное хранение.
                            <input <?if ($ITEM_PLACE == 69) echo "checked";?> type="radio" name="ITEM_PLACE" value="69">
                        </label>
                    </li>
                </ol>
            </td>
        </tr>
    </table>

    <?if ($IS_POST && !$ITEM_PLACE):?>
        <p class="errot-field" style="margin-top:0">
            <span class="filed-title">Обязательно выберите один из пунктов!</span>
        </p>
    <?endif?>
    <h3 class="input-h3<?if ($IS_POST && $NO_IMG):?> errot-field<?endif;?>">Прикрепить скан гарантийного талона или чека</h3>
    <div class="input-file"><input type="file" name="img1" accept="image/*" /></div>
    <div class="input-file"><input type="file" name="img2" accept="image/*" /></div>
    <div class="input-file"><input type="file" name="img3" accept="image/*" /></div>
    <br><br>
    <p style="font-weight:bold;">Все поля обязательны для заполнения.</p>
    <p style="font-weight:bold;">Поля которые вы не заполнили будут выделены <span style="color:red">красным цветом</span>.</p>
    <p><input class="print-it" type="submit" value="ОТПРАВИТЬ ЗАКЛЮЧЕНИЕ" /></p>
</form>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>