<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(count($arResult["ITEMS"])>0):?>
<div class="blockNav_pages">
            <div class="blockPages">
              <div class="pages">
			  <?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>
</div><!-- /.pages -->

	<?$p=0;
		if($arParams["SECTION_ID"]>0){
				$arFilter = Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "ACTIVE"=>"Y", "SECTION_ID"=>$arParams["SECTION_ID"]);
			}else{
				$arFilter = Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "ACTIVE"=>"Y");
		
			}
			
			if(strlen(trim($_REQUEST["TEXT"]))>0)
				{
					$arFilter["NAME"]="%".$_REQUEST["TEXT"]."%";
					$p++;
				}
				
			if(strlen(trim($_REQUEST["prop"]))>0)
				{
				
					if(trim($_REQUEST["prop"])=='DISCOUNT'){
						$arFilter["!PROPERTY_DISCOUNT"]=false;
						$arFilter["PROPERTY_PROP_VALUE"]="Со скидкой";
					}
					else{
					$arFilter["PROPERTY_PROP_VALUE"]=$_REQUEST["prop"];
					}
					$p++;
				}
				
		$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>$arParams["PAGE_ELEMENT_COUNT"]), array());
		$c=$res->SelectedRowsCount();
	
	?>
				<?if($p>0):?>
              <div class="blockFiltr">Найдено <strong><?=$c?></strong>  <?=fn_ReplaceForm($c, "товар", "товара" , "товаров");?></div>
			  
			  <?endif?>
            </div><!-- /.blockPages -->
<?if($_REQUEST["block"]!='none'):?>

            <div class="blockFiltr right">
              <form action="" id="change_prop" >
                <fieldset>
                  <label>Фильтр по категориям:</label>

                  <select name="prop" onchange="changeprop(this)">
				<option value="" <?if(strlen(trim($_REQUEST["prop"]))==0):?>selected<?endif?>>Не выбрано</option>	
                    <option value="Новинки" <?if($_REQUEST["prop"]=='Новинки'):?>selected<?endif?>>Новинки</option>
                    <option value="Популярные" <?if($_REQUEST["prop"]=='Популярные'):?>selected<?endif?>>Популярные</option>
                    <option value="DISCOUNT" <?if($_REQUEST["prop"]=='DISCOUNT'):?>selected<?endif?>>Со скидкой</option>
					
                  </select>
                </fieldset>
              </form>
			  
            </div><!-- .blockFiltr right -->
			
			<?endif?>
          </div>
		  
		<?if(count($arResult["ITEMS"])>0):?>
		<?if($_REQUEST["block"]!='none'):?>
<form class="search categ" action="">
            <fieldset>
				<?if($arParams["SECTION_ID"]):?>
				<?
				$res = CIBlockSection::GetByID($arParams["SECTION_ID"]);
				$ar_res = $res->GetNext();
				
				
				?>
              <div>Поиск по данной категории <strong><i><?=$ar_res["NAME"]?></i></strong></div>
			  <?else:?>
			    <div>Поиск по всему каталогу</div>
			  <?endif?>
              <input type="text" name="TEXT" value="<?=htmlspecialchars($_REQUEST["TEXT"])?>" />
              <input type="submit" value="" />
            </fieldset>
          </form>
		 <?else:?>
		 
		  <?if($_REQUEST["prop"]=='Новинки'):?>
			<?$APPLICATION->SetTitle('Новинки');?>
			<?endif?>
		  <?if($_REQUEST["prop"]=='Популярные'):?>
			<?$APPLICATION->SetTitle('Популярные');?>
			<?endif?>
		  <?if($_REQUEST["prop"]=='DISCOUNT'):?>
			<?$APPLICATION->SetTitle('Товары со скидкой');?>
			<?endif?>
		<?endif?>
		  <ul class="catalog pop both">
		  <?
			$arAkcii=array(
				"Со скидкой"=>"orange",
				"Новинки"=>"green",
				"Популярные"=>"violdark"
			);
			
		  ?>
			<?foreach($arResult["ITEMS"] as $arItem):?>
			
			<?
				$akc=$arAkcii[$arItem["PROPERTIES"]["PROP"]["VALUE"]];
			?>
			<?$href=fn_get_chainpath($arItem["IBLOCK_ID"], $arItem["IBLOCK_SECTION_ID"]).$arItem["CODE"].".html";
			
			?>
			<li>
				<?if($arItem["PROPERTIES"]["PROP"]["VALUE"]=="Новинки"):?>
              <div class="nov"></div>
			  <?endif?>
			  <?if($arItem["PROPERTIES"]["PROP"]["VALUE"]=="Со скидкой"):?>
              <div class="inderim"><?=$arItem["PROPERTIES"]["DISCOUNT"]["VALUE"]?>%</div>
			  <?endif?>
			  
                <div class="show">
                    <div>
                        <a href="<?=$href?>">
                            <?$photo = CFile::ResizeImageGet(
                                $arItem["DETAIL_PICTURE"]["ID"],
                                array("width" => "130", "height" => "130"),
                                BX_RESIZE_IMAGE_PROPORTIONAL,
                                true
                            );?>
                            <img src="<?=$photo["src"]?>" /> 
                             <?/*$arFile = CFile::GetByID($arItem["PROPERTIES"]["OPT_DETAIL_PICTURE"]["VALUE"][4])->GetNext();			
                            if($arFile["HEIGHT"]<131):
                                echo CFile::ShowImage($arItem["PROPERTIES"]["OPT_DETAIL_PICTURE"]["VALUE"][4]);
                            else:
                                echo CFile::ShowImage($arItem["PROPERTIES"]["OPT_DETAIL_PICTURE"]["VALUE"][3]);
                            endif*/?> 
                        </a>
                    </div>
                </div>
                
                <div class="text-align_right detail-block">
                    <a href="<?=$href?>">подробнее</a>
                </div>
              <div class="downLi <?=$akc?>">
                <div class="title"><a href="<?=$href?>"><?=$arItem["NAME"]?></a></div>
                <div class="price text-align_center"><?=$arItem["PRICES"]["BASE"]["PRINT_VALUE"]?></div>
                <div class="text-align_center">
                    <form action="" method="post" enctype="multipart/form-data">
                        <input type="button" class="buy-button bues" name="<?echo $arParams["ACTION_VARIABLE"]."ADD2BASKET"?>" value="Купить" data-id="<?=$arItem["ID"]?>">
                    </form><br />
                </div>
              </div><!-- /.downLi -->
            </li>
		  
		  
			<?endforeach?>
		  </ul>
		  <div class="clear"></div>
		  <div class="blockNav_pages" style="padding-top: 10px;">
            <div class="blockPages">
            <div class="blockFiltr">Найдено <strong><?=$c?></strong>  <?=fn_ReplaceForm($c, "товар", "товара" , "товаров");?></div>
           
              <div class="pages">
              			  <?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>
<?$per_page = $_SESSION["per_page"];
if (!$per_page) $per_page = "8"?>
              <div class="per-page">
                  Показывать по: 
                  <select id="show-per-page">
                      <option <?if($per_page == 8) echo 'selected '?>value="8">8</option>
                      <option <?if($per_page == 16) echo 'selected '?>value="16">16</option>
                      <option <?if($per_page == 24) echo 'selected '?>value="24">24</option>
                      <option <?if($per_page == 500) echo 'selected '?>value="all">Все</option>
                  </select>
              </div>
              </div><!-- /.pages -->
            </div><!-- /.blockPages -->
          </div>
          <?endif?>
		   <script type="text/javascript">
    		function changeprop(){
    		
    			$('#change_prop').submit();
    		}
		  </script>
		  <?endif?>
		  <?
		  if(strlen(trim($_REQUEST["TEXT"]))>0)
				{
					$arFilter["NAME"]="%".$_REQUEST["TEXT"]."%";
					$d++;
				}
				
			if(strlen(trim($_REQUEST["prop"]))>0)
				{
				
					if(trim($_REQUEST["prop"])=='DISCOUNT'){
						$arFilter["!PROPERTY_DISCOUNT"]=false;
						$arFilter["PROPERTY_PROP_VALUE"]="Со скидкой";
					}
					else{
					$arFilter["PROPERTY_PROP_VALUE"]=$_REQUEST["prop"];
					}
					$d++;
				}
		  
		  
		  ?>
		  
		  
		<?if($d>0):?>
			
			<?if(count($arResult["ITEMS"])==0):?>
				<div>По вашему запросу ничего не найдено</div><br>
				<a href="javascript:history.back()">Назад</a>
			<?endif?>
		<?endif?>
<div id="buy-complete">
    <div class="text-align_center">
        Товар добавлен в корзину
    </div>
    <div class="buttons">
        <a href="javascript:" class="bues" onclick="$.fancybox.close()">Продолжить покупки</a>
        <a href="/personal/order/make/" class="bues">Перейти к оформлению заказа</a>
    </div>
</div>
