<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
require_once $_SERVER["DOCUMENT_ROOT"].'/bitrix/templates/main_page/include/phpexcel/PHPExcel/IOFactory.php';

$APPLICATION->SetTitle("Техническое заключение");
if($_REQUEST["save"] == "y"):
    $error = false;
    if(!$_REQUEST["id"]){ $error = true; }
    if(!$_REQUEST["SC"]["NAME"]){ $error = true; }
    if(!$_REQUEST["SC"]["DATE_REPORT"]){ $error = true; }
    if(!$_REQUEST["SC"]["ADDRESS"]){ $error = true; }
    if(!$_REQUEST["SC"]["PHONE"]){ $error = true; }
    if(!$_REQUEST["OWNER"]["TITLE"]){ $error = true; }
    if(!$_REQUEST["OWNER"]["PHONE"]){ $error = true; }
    if(!$_REQUEST["OWNER"]["ADDRESS"]){ $error = true; }
		if(!$_REQUEST["ITEM"]["PRODUCTS"]){ $error = true; }
    if(!$_REQUEST["ITEM"]["MODEL"]){ $error = true; }
    if(!$_REQUEST["ITEM"]["TYPE"]){ $error = true; }
    if(!$_REQUEST["ITEM"]["CREATED"]){ $error = true; }
    if(!$_REQUEST["ITEM"]["COMPLECT"]){ $error = true; }
    if(!$_REQUEST["ITEM"]["DATE_GET"]){ $error = true; }
    if(!$_REQUEST["ITEM"]["FAULT"]){ $error = true; }
    if(!$_REQUEST["ITEM"]["REAL_FAULT"]){ $error = true; }
    if(!$_REQUEST["REPORT_FAULT"]){ $error = true; }
    if(!$_REQUEST["ITEM_PLACE"]){ $error = true; }

    if(!$error):
        $itemID = $_REQUEST["id"];
		$arProduct = CIBlockElement::GetByID($_REQUEST["ITEM"]["PRODUCTS"]);
		$product = $arProduct->GetNext()["NAME"];
		$arPropModel = CIBlockPropertyEnum::GetByID($_REQUEST["ITEM"]["MODEL"]);
		$model = $arPropModel["VALUE"];
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("SC_NAME"=>$_REQUEST["SC"]["NAME"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("DATE_REPORT"=>$_REQUEST["SC"]["DATE_REPORT"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("SC_ADDRESS"=>$_REQUEST["SC"]["ADDRESS"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("SC_PHONE"=>$_REQUEST["SC"]["PHONE"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("OWNER_TITLE"=>$_REQUEST["OWNER"]["TITLE"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("OWNER_PHONE"=>$_REQUEST["OWNER"]["PHONE"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("OWNER_ADDRESS"=>$_REQUEST["OWNER"]["ADDRESS"]));
        //CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("ITEM_NAME"=>$_REQUEST["ITEM"]["NAME"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("PRODUCT"=>$product));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("MODEL"=>$model));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("ITEM_TYPE"=>$_REQUEST["ITEM"]["TYPE"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("ITEM_CREATED"=>$_REQUEST["ITEM"]["CREATED"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("ITEM_DATE_SALE"=>$_REQUEST["ITEM"]["DATE_SALE"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("ITEM_COMPLECT"=>$_REQUEST["ITEM"]["COMPLECT"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("ITEM_DATE_GET"=>$_REQUEST["ITEM"]["DATE_GET"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("ITEM_FAULT"=>$_REQUEST["ITEM"]["FAULT"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("ITEM_REAL_FAULT"=>$_REQUEST["ITEM"]["REAL_FAULT"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("REPORT_FAULT"=>$_REQUEST["REPORT_FAULT"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("ITEM_PLACE"=>$_REQUEST["ITEM_PLACE"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("ITEM_REPAIRS"=>$_REQUEST["ITEM"]["REPAIRS"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("USER_FAULT"=>$_REQUEST["USER_FAULT"]));
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("REASON_FAIL_REPAIR"=>$_REQUEST["REASON_FAIL_REPAIR"]));
        $arFiles = Array();
        foreach($_FILES["images"]["tmp_name"] as $key=>$fileName):
            if($_FILES["images"]["error"][$key] == 4) continue;
            $arrFile = Array(
                "name" => $_FILES["images"]["name"][$key],
                "size" => $_FILES["images"]["size"][$key],
                "tmp_name" => $_FILES["images"]["tmp_name"][$key],
                "type" => $_FILES["images"]["type"][$key],
                "del" => "N",
                "MODULE_ID" => "iblock"
            );

            $arTmpFile = CFile::MakeFileArray($fileName);
            $arTmpFile["name"] = $_FILES["images"]["name"][$key];
            $arFiles[] = Array(
               "VALUE" => $arTmpFile,
               "DESCRIPTION" => $_FILES["images"]["name"][$key],
            );
        endforeach;
        if (count($arFiles)):
            CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, array("USER_IMGS" => $arFiles));
        endif;

        $book = PHPExcel_IOFactory::load("zakl_new1.xls");
        $book->getActiveSheet()->setCellValue('A1', "Техническое заключение на изделие BABYLISS №".$_REQUEST["NUMER"]);
        $book->getActiveSheet()->setCellValue('A4', $_REQUEST["SC"]["NAME"]);
        $book->getActiveSheet()->setCellValue('F3', $_REQUEST["SC"]["DATE_REPORT"]);
        $book->getActiveSheet()->setCellValue('A8', $_REQUEST["SC"]["ADDRESS"]);
        $book->getActiveSheet()->setCellValue('D7', $_REQUEST["SC"]["PHONE"]);

        $book->getActiveSheet()->setCellValue('B10', $_REQUEST["OWNER"]["TITLE"]);
        $book->getActiveSheet()->setCellValue('H10', $_REQUEST["OWNER"]["PHONE"]);
        $book->getActiveSheet()->setCellValue('A11', $_REQUEST["OWNER"]["ADDRESS"]);

        $book->getActiveSheet()->setCellValue('C13', $product);
        $book->getActiveSheet()->setCellValue('C14', $model);
        $book->getActiveSheet()->setCellValue('H12', $_REQUEST["ITEM"]["TYPE"]);
        $book->getActiveSheet()->setCellValue('I14', $_REQUEST["ITEM"]["CREATED"]);
        $book->getActiveSheet()->setCellValue('C15', $_REQUEST["ITEM"]["DATE_SALE"]);

        foreach ($_REQUEST["ITEM"]["COMPLECT"] as $key=>$propID):
            $arComplect = CIBlockPropertyEnum::GetByID($propID);
            if($key > 0) $COMPLECT .= ", ";
            $COMPLECT .= $arComplect["VALUE"];
        endforeach;

        $book->getActiveSheet()->setCellValue('C16', $COMPLECT);
        $book->getActiveSheet()->setCellValue('E17', $_REQUEST["ITEM"]["DATE_GET"]);
        $book->getActiveSheet()->setCellValue('A19', $_REQUEST["ITEM"]["FAULT"]);
        $book->getActiveSheet()->setCellValue('E20', $_REQUEST["ITEM"]["REPAIRS"]);
        $book->getActiveSheet()->setCellValue('D23', $_REQUEST["ITEM"]["REAL_FAULT"]);

        $arPropReportFault = CIBlockPropertyEnum::GetByID($_REQUEST["REPORT_FAULT"]);
        $book->getActiveSheet()->setCellValue('F24', $arPropReportFault["VALUE"]);
        if($_REQUEST["REPORT_FAULT"] == "64"):
            $book->getActiveSheet()->setCellValue('A25', $_REQUEST["USER_FAULT"]);
        endif;

        $arPropReasonFailRepair = CIBlockPropertyEnum::GetByID($_REQUEST["REASON_FAIL_REPAIR"]);
        $book->getActiveSheet()->setCellValue('A34', $arPropReasonFailRepair["VALUE"]);

        $arPropItemPlace = CIBlockPropertyEnum::GetByID($_REQUEST["ITEM_PLACE"]);
        $book->getActiveSheet()->setCellValue('A36', $arPropItemPlace["VALUE"]);

        $objWriter = PHPExcel_IOFactory::createWriter($book, 'Excel5');
        $objWriter->save("new_tz_".str_replace('/', '_', $_REQUEST["NUMER"]).".xls");
        CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, Array("FORMA"=>CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/partneram/babyliss/tech-form/new_tz_".str_replace('/', '_', $_REQUEST["NUMER"]).".xls")));

        if($_REQUEST["send"] == "on"):
            CIBlockElement::SetPropertyValuesEx($itemID, IB_REPORTS, array("STATUS" => 70));
            $arEventFields = array(
                "NOMER"             => $_REQUEST["NUMER"],
                "DATA_OTPR"         => date("d.m.Y"),
                "URL"               => "http://".SITE_SERVER_NAME."/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=10&type=region&ID=".$itemID."&lang=ru&find_section_section=0&WF=Y"
            );
            $item = CIBlockElement::GetList(Array(), $arFilter, false, Array("nTopCount"=>1), Array("ID", "PROPERTY_FORMA"))->GetNext();
            CEvent::Send("TEH_ZAKL_EDIT", "s1", $arEventFields, "N", "", array($item["PROPERTY_FORMA_VALUE"]));
        endif;

        $ok = true;
    endif;
endif;

$arSelect = Array(
    "ID",
    "PROPERTY_NUMER",
    "PROPERTY_USER",
    "PROPERTY_SC_NAME",
    "PROPERTY_SC_PHONE",
    "PROPERTY_SC_ADDRESS",
    "PROPERTY_OWNER_TITLE",
    "PROPERTY_OWNER_PHONE",
    "PROPERTY_OWNER_ADDRESS",
    "PROPERTY_ITEM_NAME",
    "PROPERTY_ITEM_TYPE",
    "PROPERTY_ITEM_CREATED",
    "PROPERTY_ITEM_FAULT",
    "PROPERTY_MODEL",
    "PROPERTY_ITEM_REPAIRS",
    "PROPERTY_ITEM_REAL_FAULT",
    "PROPERTY_PRODUCT",
    "PROPERTY_ITEM_DATE_SALE",
    "PROPERTY_ITEM_DATE_GET",
    "PROPERTY_ITEM_COMPLECT",
    "PROPERTY_DATE_REPORT",
    "PROPERTY_USER_IMGS",
    "PROPERTY_REPORT_FAULT",
    "PROPERTY_USER_FAULT",
    "PROPERTY_REASON_FAIL_REPAIR",
    "PROPERTY_ITEM_PLACE",
    "PROPERTY_STATUS",
    "PROPERTY_USER"
);
$arItem = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>IB_REPORTS, "ID"=>$_REQUEST["id"]), false, false, $arSelect)->GetNext();
?>
<pre style="display:none"><?print_r($arItem);?></pre>
<script>
$(function(){
	$("#sub-button").click(function(){
		var sended = $("#send-proof").prop('checked');
		if (!sended){
			alert('Необходимо отметить пункт "Отправить на проверку"');
			return false;
		}
	});
	
	
});
function changeModels(){
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
<form id="edit-report" enctype="multipart/form-data" action="" method="post">
    <?if($ok):?>
        <table class="half">
            <tr>
                <td colspan="2" class="tcenter fielt-title ok-field">
                    <strong>
                        Изменения успешно сохранены<?if($_REQUEST["send"] == "on"):?> и отправлены на проверку<?endif?>!
                    </strong>
                </td>
            </tr>
        </table>
    <?endif?>
    <?if($arItem["PROPERTY_STATUS_ENUM_ID"] != 61 || $arItem["PROPERTY_USER_VALUE"] != $USER->GetID()):?>
        <table class="half">
            <caption>Эту заявку невозможно отредактировать!</caption>
        </table>
    <?else:?>
        <input type="hidden" name="save" value="y" />
        <input type="hidden" name="id" value="<?=$_REQUEST["id"] ? $_REQUEST["id"] : $arItem["ID"]?>" />
        <input type="hidden" name="NUMER" value="<?=$arItem["PROPERTY_NUMER_VALUE"]?>" />
        <table class="half">
            <caption class="big">Техническое заключение на изделие BABYLISS №<?=$arItem["PROPERTY_NUMER_VALUE"]?></caption>
            <?if($error):?>
                <tr>
                    <td colspan="2" class="tcenter fielt-title error-field">
                        <strong>
                            Форма не сохранена!<br />
                            Не заполненые параметры в форме, заменены прежними значениями
                        </strong>
                    </td>
                </tr>
            <?endif?>

            <tr>
                <td class="field-title<?if(!$arItem["PROPERTY_SC_NAME_VALUE"]):?> error-field<?endif?>">
                    Выдано Сервисным Центром:
                </td>
                <td class="field-input">
                    <input type="text" name="SC[NAME]" value="<?=$arItem["PROPERTY_SC_NAME_VALUE"]?>" placeholder="Название СЦ" />
                </td>
            </tr>
            <tr>
                <td class="field-title<?if(!$arItem["PROPERTY_DATE_REPORT_VALUE"]):?> error-field<?endif?>">
                    Дата выдачи заключения:
                </td>
                <td class="field-input">
                    <input type="text" placeholder="01.01.20<?=date("y")?>" name="SC[DATE_REPORT]" value="<?=$arItem["PROPERTY_DATE_REPORT_VALUE"]?>" />
                </td>
            </tr>
            <tr class="valign-combo_top">
                <td class="field-title<?if(!$arItem["PROPERTY_SC_ADDRESS_VALUE"]):?> error-field<?endif?>">
                    Адрес сервисного центра:
                </td>
                <td class="field-input">
                    <textarea name="SC[ADDRESS]"><?=$arItem["PROPERTY_SC_ADDRESS_VALUE"]?></textarea>
                </td>
            </tr>
            <tr>
                <td class="field-title<?if(!$arItem["PROPERTY_SC_PHONE_VALUE"]):?> error-field<?endif?>">
                    Телефон СЦ:
                </td>
                <td class="field-input">
                    <input type="text" name="SC[PHONE]" value="<?=$arItem["PROPERTY_SC_PHONE_VALUE"]?>" />
                </td>
            </tr>
        </table>

        <table class="half">
            <caption>I. Данные о владельце изделия</caption>
            <tr>
                <td class="field-title<?if(!$arItem["PROPERTY_OWNER_TITLE_VALUE"]):?> error-field<?endif?>">
                    ФИО владельца:
                </td>
                <td class="field-input">
                    <input type="text" name="OWNER[TITLE]" value="<?=$arItem["PROPERTY_OWNER_TITLE_VALUE"]?>" />
                </td>
            </tr>
            <tr>
                <td class="field-title<?if(!$arItem["PROPERTY_OWNER_PHONE_VALUE"]):?> error-field<?endif?>">
                    Телефон владельца:
                </td>
                <td class="field-input">
                    <input type="text" placeholder="01.01.20<?=date("y")?>" name="OWNER[PHONE]" value="<?=$arItem["PROPERTY_OWNER_PHONE_VALUE"]?>" />
                </td>
            </tr>
            <tr class="valign-combo_top">
                <td class="field-title<?if(!$arItem["PROPERTY_OWNER_ADDRESS_VALUE"]):?> error-field<?endif?>">
                    Адрес владельца:
                </td>
                <td class="field-input">
                    <textarea name="OWNER[ADDRESS]"><?=$arItem["PROPERTY_OWNER_ADDRESS_VALUE"]?></textarea>
                </td>
            </tr>
        </table>

				
        <table class="half">
            <caption>II. Данные об изделии</caption>
            <tr>
                <td class="field-title">
                    Наименование:
                </td>
                <td class="field-input">
                    <?//$arProducts = CIBlockPropertyEnum::GetList(Array("VALUE"=>"ASC"), Array("IBLOCK_ID"=>IB_REPORTS, "CODE"=>"ITEM_PRODUCTS"));?>
					<?$arProducts = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>11, "ACTIVE"=>"Y"), false, false, Array("ID", "NAME"));?>
                    <select name="ITEM[PRODUCTS]" id="product-select" onChange="changeModels()">
						<?
						if ($product = $arProducts->GetNext()){
							print_r($product);
							?><option value="<?=$product["ID"]?>"<?if($arItem["PROPERTY_PRODUCT_VALUE"] == $product["NAME"]):?> selected=""<?endif?>><?=$product["NAME"]?></option><?
							$product_id = $product['ID'];
						}
						while ($product = $arProducts->GetNext()){
							?><option value="<?=$product["ID"]?>"<?if($arItem["PROPERTY_PRODUCT_VALUE"] == $product["NAME"]):?> selected=""<?$product_id = $product['ID'];?><?endif?>><?=$product["NAME"]?></option><?
						}
						?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="field-title">
                    Модель:
                </td>

                <td class="field-input">
                    <?//$arModels = CIBlockPropertyEnum::GetList(Array("VALUE"=>"ASC"), Array("IBLOCK_ID"=>10, "CODE"=>"ITEM_MODEL"));?>
					<?$arModels = CIBlockElement::GetProperty(11, $product_id, "sort", "asc", array("CODE"=>"MODELS"));?>
                    <select class="input-form" name="ITEM[MODEL]" id="model-select">
						<?while($arModel = $arModels->GetNext()):?>
							<option value="<?=$arModel["VALUE"]?>"<?if($arItem["PROPERTY_MODEL_VALUE"] == $arModel["VALUE_ENUM"]):?> selected=""<?endif?>><?=$arModel["VALUE_ENUM"]?></option>
						<?endwhile?>
                    </select>
                </td>
                <?/*
                <td class="field-input">
                    <input type="text" name="ITEM[NAME]" value="<?=$arItem["PROPERTY_ITEM_NAME_VALUE"]?>" />
                </td>
            </tr>*/?>
            <tr>
                <td class="field-title<?if(!$arItem["PROPERTY_ITEM_TYPE_VALUE"]):?> error-field<?endif?>">
                    Тип изделия:
                </td>
                <td class="field-input">
                    <input type="text" name="ITEM[TYPE]" value="<?=$arItem["PROPERTY_ITEM_TYPE_VALUE"]?>" />
                </td>
            </tr>
            <tr>
                <td class="field-title<?if(!$arItem["PROPERTY_ITEM_CREATED_VALUE"]):?> error-field<?endif?>">
                    Дата производства:
                </td>
                <td class="field-input">
                    <input maxlength="10" type="text" name="ITEM[CREATED]" value="<?=$arItem["PROPERTY_ITEM_CREATED_VALUE"]?>" />
                </td>
            </tr>
            <tr>
                <td class="field-title">
                    Дата продажи:
                </td>
                <td class="field-input">
                    <input type="text" name="ITEM[DATE_SALE]" placeholder="01.01.20<?=date("y")?>" value="<?=$arItem["PROPERTY_ITEM_DATE_SALE_VALUE"]?>" />
                </td>
            </tr>
            <tr>
                <td class="field-title<?if(!$arItem["PROPERTY_ITEM_COMPLECT_VALUE"]):?> error-field<?endif?>">
                    Комплектность:
                </td>
                <td class="field-input">
                    <ul class="checkbox-list">
                        <?$arComplects = CIBlockPropertyEnum::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>IB_REPORTS, "CODE"=>"ITEM_COMPLECT"));
                        while($arComplect = $arComplects->GetNext()):?>
                            <li>
                                <label>
                                    <input <?if (in_array($arComplect["ID"], array_keys($arItem["PROPERTY_ITEM_COMPLECT_VALUE"]))) echo "checked";?> type="checkbox" name="ITEM[COMPLECT][]" value="<?=$arComplect["ID"]?>">
                                    <?=$arComplect["VALUE"]?>
                                </label>
                            </li>
                        <?endwhile?>
                    </ul>
                </td>
            </tr>
            <tr>
                <td class="field-title<?if(!$arItem["PROPERTY_ITEM_DATE_GET_VALUE"]):?> error-field<?endif?>">
                    Дата поступления в СЦ:
                </td>
                <td class="field-input">
                    <input type="text" name="ITEM[DATE_GET]" value="<?=$arItem["PROPERTY_ITEM_DATE_GET_VALUE"]?>" />
                </td>
            </tr>
            <tr class="valign-combo_top">
                <td class="field-title<?if(!$arItem["PROPERTY_ITEM_FAULT_VALUE"]):?> error-field<?endif?>">
                    Внешние признаки неисправности:
                </td>
                <td class="field-input">
                    <textarea name="ITEM[FAULT]"><?=$arItem["PROPERTY_ITEM_FAULT_VALUE"]?></textarea>
                </td>
            </tr>
            <tr class="valign-combo_top">
                <td class="field-title">
                    Сведения о предыдущих ремонтах:
                </td>
                <td class="field-input">
                    <textarea name="ITEM[REPAIRS]" placeholder="№ гарантийной квитанции, даты приема и выдачи изделия"><?=$arItem["PROPERTY_ITEM_REPAIRS_VALUE"]?></textarea>
                </td>
            </tr>
        </table>

        <table class="half">
            <caption>III. Данные освидетельствования</caption>
            <tr class="valign-combo_top">
                <td class="field-title<?if(!$arItem["PROPERTY_ITEM_REAL_FAULT_VALUE"]):?> error-field<?endif?>">
                    Выявленный дефект:
                </td>
                <td class="field-input">
                    <textarea name="ITEM[REAL_FAULT]"><?=$arItem["PROPERTY_ITEM_REAL_FAULT_VALUE"]?></textarea>
                </td>
            </tr>
            <tr class="valign-combo_top">
                <td class="field-title<?if(!$arItem["PROPERTY_REPORT_FAULT_VALUE"]):?> error-field<?endif?>">
                    Заключение о причинах неисправности:
                </td>
                <td class="field-input">
                    <ul class="checkbox-list">
                        <?$arFaults = CIBlockPropertyEnum::GetList(Array("ID"=>"ASC"), Array("IBLOCK_ID"=>IB_REPORTS, "CODE"=>"REPORT_FAULT"));
                        while($arFault = $arFaults->GetNext()):?>
                            <li>
                                <label>
                                    <input <?if($arFault["ID"] == $arItem["PROPERTY_REPORT_FAULT_ENUM_ID"]) echo "checked";?> type="radio" name="REPORT_FAULT" value="<?=$arFault["ID"]?>">
                                    <?=$arFault["VALUE"]?>
                                </label>
                            </li>
                        <?endwhile?>
                    </ul>
                    <textarea name="USER_FAULT"<?if($arItem["PROPERTY_REPORT_FAULT_ENUM_ID"] != "64"):?> disabled=""<?endif?>><?if($arItem["PROPERTY_REPORT_FAULT_ENUM_ID"] == "64" && $arItem["PROPERTY_USER_FAULT_VALUE"]) echo $arItem["PROPERTY_USER_FAULT_VALUE"]?></textarea>
                </td>
            </tr>
        </table>

        <table class="half">
            <caption>IV. Причина невозможности ремонта</caption>
            <tr>
                <td class="field-input align_left" colspan="2">
                    <ul class="checkbox-list">
                        <li>
                            <label>
                                <input <?if(!$arItem["PROPERTY_REASON_FAIL_REPAIR_ENUM_ID"]) echo "checked";?> type="radio" name="REASON_FAIL_REPAIR" value="0">
                                Не выбрано
                            </label>
                        </li>
                        <?$arReasons = CIBlockPropertyEnum::GetList(Array("ID"=>"ASC"), Array("IBLOCK_ID"=>IB_REPORTS, "CODE"=>"REASON_FAIL_REPAIR"));
                        while($arReason = $arReasons->GetNext()):?>
                            <li>
                                <label>
                                    <input <?if($arReason["ID"] == $arItem["PROPERTY_REASON_FAIL_REPAIR_ENUM_ID"]) echo "checked";?> type="radio" name="REASON_FAIL_REPAIR" value="<?=$arReason["ID"]?>">
                                    <?=$arReason["VALUE"]?>
                                </label>
                            </li>
                        <?endwhile?>
                    </ul>
                </td>
            </tr>
        </table>

        <table class="half">
            <caption>V. Местонахождение изделия после технического освидетельствования</caption>
            <tr>
                <td class="field-input align_left" colspan="2">
                    <ul class="checkbox-list">
                        <?$arItemPlaces = CIBlockPropertyEnum::GetList(Array("ID"=>"ASC"), Array("IBLOCK_ID"=>IB_REPORTS, "CODE"=>"ITEM_PLACE"));
                        while($arItemPlace = $arItemPlaces->GetNext()):?>
                            <li>
                                <label>
                                    <input <?if($arItemPlace["ID"] == $arItem["PROPERTY_ITEM_PLACE_ENUM_ID"]) echo "checked";?> type="radio" name="ITEM_PLACE" value="<?=$arItemPlace["ID"]?>">
                                    <?=$arItemPlace["VALUE"]?>
                                </label>
                            </li>
                        <?endwhile?>
                    </ul>
                </td>
            </tr>
            <?if (!$arItem["PROPERTY_ITEM_PLACE_VALUE"]):?>
                <tr>
                    <td class="error-field filed-title align_left" colspan="2">
                        Обязательно выберите один из пунктов!
                    </td>
                </tr>
            <?endif?>
        </table>

        <table class="third file-table">
            <caption>Прикрепить скан гарантийного талона или чека</caption>
            <tr>
                <td>
                    <?if($arItem["PROPERTY_USER_IMGS_VALUE"][0]):
                        $photo = CFile::ResizeImageGet(
                            $arItem["PROPERTY_USER_IMGS_VALUE"][0],
                            array("width" => "150", "height" => "150"),
                            BX_RESIZE_IMAGE_PROPORTIONAL,
                            true
                        );?>
                        <a href="<?=CFile::GetPath($arItem["PROPERTY_USER_IMGS_VALUE"][0])?>" class="fancy">
                            <img src="<?=$photo["src"]?>" />
                        </a><br />
                        <label>
                            <span>Заменить</span>
                            <input type="file" name="images[]" accept="image/*" />
                        </label>
                    <?else:?>
                        <label>
                            <span>Выбрать</span>
                            <input type="file" name="images[]" accept="image/*" />
                        </label>
                    <?endif?>
                </td>
                <td>
                    <?if($arItem["PROPERTY_USER_IMGS_VALUE"][1]):
                        $photo = CFile::ResizeImageGet(
                            $arItem["PROPERTY_USER_IMGS_VALUE"][1],
                            array("width" => "150", "height" => "150"),
                            BX_RESIZE_IMAGE_PROPORTIONAL,
                            true
                        );?>
                        <a href="<?=CFile::GetPath($arItem["PROPERTY_USER_IMGS_VALUE"][1])?>" class="fancy">
                            <img src="<?=$photo["src"]?>" />
                        </a><br />
                        <label>
                            <span>Заменить</span>
                            <input type="file" name="images[]" accept="image/*" />
                        </label>
                    <?else:?>
                        <label>
                            <span>Выбрать</span>
                            <input type="file" name="images[]" accept="image/*" />
                        </label>
                    <?endif?>
                </td>
                <td>
                    <?if($arItem["PROPERTY_USER_IMGS_VALUE"][2]):
                        $photo = CFile::ResizeImageGet(
                            $arItem["PROPERTY_USER_IMGS_VALUE"][2],
                            array("width" => "150", "height" => "150"),
                            BX_RESIZE_IMAGE_PROPORTIONAL,
                            true
                        );?>
                        <a href="<?=CFile::GetPath($arItem["PROPERTY_USER_IMGS_VALUE"][2])?>" class="fancy">
                            <img src="<?=$photo["src"]?>" />
                        </a><br />
                        <label>
                            <span>Заменить</span>
                            <input type="file" name="images[]" accept="image/*" />
                        </label>
                    <?else:?>
                        <label>
                            <span>Выбрать</span>
                            <input type="file" name="images[]" accept="image/*" />
                        </label>
                    <?endif?>
                </td>
            </tr>
            <tr>
                <td colspan="3" class="tcenter field-title error-field">
                    <strong>
                        Внимание! При замене хотя бы одной из фотографий, остальные будут удалены!<br />
                        Необходимо заменять сразу все фотографии! Будьте внимательны!
                    </strong>
                </td>
            </tr>
        </table>
        <br>
        <p style="font-weight:bold;">Все поля кроме сведений о предыдущих ремонтах обязательны для заполнения.</p>
        <p style="font-weight:bold;">Поля, которые Вы не заполнили, будут выделены <span style="color:red">красным цветом</span>.</p>
        <div class="tcenter" style="margin: 15px 0">
            <label>
                <input type="checkbox" id="send-proof" name="send" required="required"/>Отправить на проверку (дальнейшее редактирование будет не возможно), обязательно 
            </label>
        </div>
        <div class="tcenter">
            <input class="print-it" id="sub-button" type="submit" value="СОХРАНИТЬ ИЗМЕНЕНИЯ И ОТПРАВИТЬ НА ПРОВЕРКУ" />
        </div>
    <?endif?>
</form>
<style>
    #edit-report {
        margin: 0;
        padding: 0;
        width: 520px;
    }
    #edit-report table {
        width: 100%;
        border-collapse: collapse;
    }
    #edit-report table caption {
        color: #737376;
        margin: 20px 0 10px;
        font-size: 18px;
    }
    #edit-report table caption.big {
        margin: 0px 0 20px;
        font-size: 22px;
        text-transform: uppercase;
    }
    #edit-report table.third td {
        width: 33%;
        border: none;
        text-align: center;
    }
    #edit-report table.half td {
        width: 60%;
        border: none;
        text-align: left;
    }
    #edit-report table.half td:first-child {
        text-align: right;
        width: 40%;
    }
    #edit-report table.half td.field-title {
        font-weight: bold;
        color: #737373;
        padding-right: 5px;
        vertical-align: middle;
    }
    #edit-report table.half tr.valign-combo_top td {
        vertical-align: top;
    }
    #edit-report table.half tr.valign-combo_top .field-title {
        padding-top: 5px;
    }
    #edit-report table.half tr.valign-combo_top td ul.checkbox-list {
        margin-bottom: 10px;
    }
    .error-field {
        color: #FF0000 !important;
    }
    .ok-field {
        color: #00FF00 !important;
    }

    #edit-report table.half td.align_left {
        text-align: left;
    }
    #edit-report table.half td.field-input input[type=text],
    #edit-report table.half td.field-input textarea,
    #edit-report table.half td.field-input select {
        width: 100%;
        box-sizing: border-box;
        padding: 5px;
        font: normal 12px tahoma, arial, verdana;
    }
    #edit-report table.half td.field-input select {
        padding-left: 2px;
    }
    #edit-report table.half td.field-input textarea {
        height: 60px;
        resize: none;
    }
    #edit-report table.half td.field-input ul.checkbox-list {
        margin: 0;
        padding: 0;
    }
    #edit-report table.half td.field-input ul.checkbox-list li {
        list-style: none;
    }
    #edit-report table.half td.field-input label {
        cursor: pointer;
    }
    #edit-report table.half td.field-input ul.checkbox-list li label input {
        position: relative;
        top: 2px;
    }
    #edit-report .file-table a {
        text-decoration: none;
    }
    #edit-report .file-table label input[type=file] {
        display: none;
    }
    #edit-report .file-table label span {
        cursor: pointer;
        text-decoration: underline;
        display: block;
        margin-top: 15px;
    }
</style>
<script>
    $(function(){
        $('input[name="SC[PHONE]"], input[name="OWNER[PHONE]"]').inputmask('+7 (999) 999-99-99', {
            'placeholder': "+7 (___) ___-__-__"
        });

        $('input[name="SC[DATE_REPORT]"],input[name="ITEM[DATE_SALE]"], input[name="ITEM[DATE_GET]"]').inputmask('99.99.2099', {
            'placeholder': "__.__.20__"
        });

        $('input[name="REPORT_FAULT"').on('change', function(){
            console.log($(this).val());
            $("textarea[name=USER_FAULT]").prop('disabled', true);
            if($(this).val() == 64) $("textarea[name=USER_FAULT]").removeProp('disabled');
        });
        $('input[type="file"]').on('change', function(){
            var $this = $(this);
            $this.prev('span').html($this.val().replace('C:\\fakepath\\', ''));
        });
    });
</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>

<?/*
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
        </tbody>
    </table>
*/?>