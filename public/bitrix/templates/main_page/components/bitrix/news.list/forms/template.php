<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if (count($arResult["ITEMS"])>0){?>
    <table class="table-form">
        <thead>
            <tr>
                <th>Дата отправки формы</th>
                <th>Номер</th>
                <th>Скачать форму</th>
                <th>Статус</th>
                <th>Чек или гарантийный талоны</th>
                <th>Замечания</th>
                <th>Скриншот</th>
            </tr>
        </thead>
        <tbody>
            <?foreach($arResult["ITEMS"] as $arItem){
                $imgs = array();
                if ($arItem["DISPLAY_PROPERTIES"]["USER_IMGS"]["FILE_VALUE"][0]){
                    $imgs = $arItem["DISPLAY_PROPERTIES"]["USER_IMGS"]["FILE_VALUE"];
                } else {
                    if ($arItem["DISPLAY_PROPERTIES"]["USER_IMGS"]["FILE_VALUE"]) $imgs[0] = $arItem["DISPLAY_PROPERTIES"]["USER_IMGS"]["FILE_VALUE"];
                }
                $resImgs = array();
                foreach($imgs as $img){
                    $resImgs[] = CFile::ResizeImageGet($img["ID"], array('width'=>60, 'height'=>500), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
                }
                ?>
                <tr>
                    <td><?=$arItem["ACTIVE_FROM"]?></td>
                    <td><?=$arItem["DISPLAY_PROPERTIES"]["NUMER"]["VALUE"]?></td>
                    <td>
                        <?if(($arItem["PROPERTIES"]["STATUS"]["VALUE_ENUM_ID"] == 60) && ($arItem["PROPERTIES"]["APPROVED"]["VALUE"] == 1)):?>
                            <?=$arItem["DISPLAY_PROPERTIES"]["FORMA"]["DISPLAY_VALUE"]?>
                        <?endif?>
                    </td>
                    <td>
                        <?=$arItem["DISPLAY_PROPERTIES"]["STATUS"]["VALUE"]?>
                        <?if($arItem["PROPERTIES"]["STATUS"]["VALUE_ENUM_ID"] == 61):?><br />
                            <a href="edit.php?id=<?=$arItem["ID"]?>">редактировать</a>
                        <?endif?>
                    </td>
                    <td><?
                        foreach($resImgs as $key=>$resImg){?>
                        <a class="img-fancy" href="<?=$imgs[$key]["SRC"]?>"><img src="<?=$resImg["src"]?>" alt="" /></a>
                        <?}
                    ?></td>
                    <td><?=$arItem["PREVIEW_TEXT"]?></td>
                    <td>
                        <? if ($arItem["PREVIEW_PICTURE"]){?>
                            <?$file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width'=>60, 'height'=>500), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);?>                
                            <a class="img-fancy" href="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"><img src="<?=$file["src"]?>" alt="" /></a>
                        <?}?>
                    </td>
                </tr>
            <?}?>
        </tbody>
    </table>
	<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
		<br /><?=$arResult["NAV_STRING"]?>
	<?endif;?>
<?}?>
