<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); 

use App\View as v;
use Core\Strings as str;
?>
<form action="">
    <input name="q" type="text" value="<?= $arResult['REQUEST']['QUERY'] ?>" autocomplete="off">
    <button type="submit"></button>
</form>
<div class="editable-area default-page">
    <? if (isset($arResult['REQUEST']['ORIGINAL_QUERY'])): ?>
        <p>
            Исправлена раскладка клавиатуры в «<a href="<?= $arResult['ORIGINAL_QUERY_URL'] ?>"><?= $arResult['REQUEST']['ORIGINAL_QUERY'] ?></a>»
        </p>
    <? endif ?>
    <? if (v::isEmpty($arResult['SEARCH'])): ?>
        <div class="h3">
            По вашему запросу ничего не найдено.
        </div>
    <? endif ?>
</div>
<div class="search-items">
    <?foreach($arResult["SEARCH"] as $arItem):?>
        <?
        // TODO refactor
        if(preg_match('#S#',$arItem["ITEM_ID"]))
        {
            $idsection=preg_replace('#S(\d+)#','$1',$arItem["ITEM_ID"]);
            $arSection=GetIBlockSection($idsection);
            $arItem["URL"]=fn_get_chainpath($arSection["IBLOCK_ID"], $arSection["ID"]);
            $photo = CFile::ResizeImageGet(
                $arSection["PICTURE"],
                array("width" => "150", "height" => "150"),
                BX_RESIZE_IMAGE_PROPORTIONAL,
                true
            );
        }
        elseif($arItem["ITEM_ID"]>0){
            $arElement=GetIBlockElement($arItem["ITEM_ID"]);
            $path = fn_get_chainpath($arElement["IBLOCK_ID"], $arElement["IBLOCK_SECTION_ID"]);
            if (str::startsWith($path, v::path('catalog'))) {
                $arItem["URL"]=$path.$arElement["CODE"].".html";
            }
            $photo = CFile::ResizeImageGet(
                $arElement["DETAIL_PICTURE"],
                array("width" => "150", "height" => "150"),
                BX_RESIZE_IMAGE_PROPORTIONAL,
                true
            );
        } else {
            $photo = null;
        }?>
        <div class="search-item">
            <a class="item-image" href="<?=$arItem["URL"]?>">
                <?if($photo):?>
                    <img src="<?=$photo["src"]?>" />
                <?endif?>
            </a>
            <div class="item-text">
                <div class="name h4"><a href="<?echo $arItem["URL"]?>"><?echo $arItem["TITLE_FORMATED"]?></a></div>
                <div class="search-preview"><?echo $arItem["BODY_FORMATED"]?></div>
            </div>
        </div>
    <?endforeach;?>
</div>
