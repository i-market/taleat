<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$howCheckUrl = $arResult["PROPERTIES"]["HOW_CHECK_URL"]["VALUE"];
$otherUrl = $arResult["PROPERTIES"]["OTHER_SITE_URL"]["VALUE"];

_c($arResult["PROPERTIES"]);
?>
<div class="blockTovar">
    <div class="sideleft">
        <div class="imgBlock">
            <a class="fancybox" href="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>">
            <?$photo = CFile::ResizeImageGet(
                $arResult["DETAIL_PICTURE"]["ID"],
                array("width" => "280", "height" => "700"),
                BX_RESIZE_IMAGE_PROPORTIONAL,
                true
            );
            /*=CFile::ShowImage($arResult["PROPERTIES"]["OPT_DETAIL_PICTURE"]["VALUE"][6])*/?>
            <img src="<?=$photo["src"]?>" />
            </a>
        </div>
        <?if($howCheckUrl):?>
            <div style="text-align:center; <?if($otherUrl):?> margin-bottom: 10px<?endif?>" class="prop_val">
                <a class="dop-link bues" href="<?=$howCheckUrl?>" target="_blank">Проверить тип изделия</a>
            </div>
        <?endif?>
        
        <?if($otherUrl):?>
            <div style="text-align:left;" class="prop_val">
                <span>Ссылка на сайт:</span> <a class="dop-link" href="<?=$otherUrl?>" target="_blank"><?=$otherUrl?></a>
            </div>              
        <?endif?>
    </div><!-- /.sideleft -->
    
    <div class="sidecenter">
        <h3>Описание товара</h3>
        <?if(strlen(trim($arResult["PROPERTIES"]["ARTNUMBER"]["VALUE"]))>0):?>
            <div class="prop_val">
                <span>Артикул :</span> <?=$arResult["PROPERTIES"]["ARTNUMBER"]["VALUE"]?>
            </div>
        <?endif?>
        
        <?if(strlen(trim($arResult["PROPERTIES"]["IN_MODEL"]["VALUE"]))>0):?>
            <div class="prop_val">
                <span>К моделям:</span> <?=$arResult["PROPERTIES"]["IN_MODEL"]["VALUE"]?>
            </div>
        <?endif?>
        
        <?if(strlen(trim($arResult["PROPERTIES"]["IN_TYPE"]["VALUE"]))>0):?>
            <div class="prop_val">
                <span>К типу :</span> <?=$arResult["PROPERTIES"]["IN_TYPE"]["VALUE"]?>
            </div>
        <?endif?>
        
        <?if($arResult["PROPERTIES"]["PROP"]["VALUE"]=="Со скидкой"):?>
            <?if($arResult["PROPERTIES"]["DISCOUNT"]["VALUE"]):?>
                <div class="prop_val">
                    <span>Скидка :</span> <?=$arResult["PROPERTIES"]["DISCOUNT"]["VALUE"]?>
                </div>
            <?endif?>
        <?endif?>
        <br />
        <div class="txt">
            <?=$arResult["DETAIL_TEXT"]?>
        </div>
        <?if(strlen(trim($arResult["PROPERTIES"]["OLD_PRICE"]["VALUE"]))>0):?>
            <div class="old_val">
                <?=$arResult["PROPERTIES"]["OLD_PRICE"]["VALUE"]?> руб.
            </div>
        <?endif?>
        
        <?foreach ($arResult["PRICE_MATRIX"]["ROWS"] as $ind => $arQuantity):
            foreach($arResult["PRICE_MATRIX"]["COLS"] as $typeID => $arType):
                echo ' <div class="price">'.FormatCurrency($arResult["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["PRICE"], $arResult["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["CURRENCY"])."</div>";
            endforeach;
        endforeach?>
        
        <form action="<?=POST_FORM_ACTION_URI?>" method="post" enctype="multipart/form-data">            
            <input type="button" class="buy-button bues" data-id="<?=$arResult["ID"]?>" value="Купить">
        </form>
    </div><!-- /sidecenter -->
</div>

<br />

<a href="javascript:history.back()">Назад</a>

<div id="buy-complete">
    <div class="text-align_center">
        Товар добавлен в корзину
    </div>
    <div class="buttons">
        <a href="javascript:" class="bues" onclick="$.fancybox.close()">Продолжить покупки</a>
        <a href="/personal/order/make/" class="bues">Перейти к оформлению заказа</a>
    </div>
</div>