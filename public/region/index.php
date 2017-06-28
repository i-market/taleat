<?
require ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Сетки к бритвам, зубные насадки к электрическим зубным щеткам, запасные части к Браун, Braun, аксессуары к бытовой технике");
$APPLICATION->SetPageProperty("description", "Мы предлагаем широкий ассортимент аксессуаров  к бытовой технике  фирмы Braun (Браун)");
$APPLICATION->SetTitle("Региональные сервис-центры Браун BRAUN и Babyliss PARIS");
?>

<? $APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW"=>"file", "PATH"=>"/include/text_in_main_page.php", "EDIT_TEMPLATE"=>""), false); ?>
<br><br>
<div style="text-align:center;">
<?$db_enum_list = CIBlockProperty::GetPropertyEnum("BRANDS", Array("sort "=>"asc"), Array("IBLOCK_ID"=>7));
while($ar_enum_list = $db_enum_list->GetNext()){
    if($APPLICATION->GetProperty("hide_braun_beauty") == "Y" && $ar_enum_list["ID"] == 32) continue;
    if($APPLICATION->GetProperty("hide_braun_kitchen") == "Y" && $ar_enum_list["ID"] == 33) continue;
    if($APPLICATION->GetProperty("hide_babyliss") == "Y" && $ar_enum_list["ID"] == 34) continue;
    $arCity = array();
    $res = CIBlockElement::GetList(Array("NAME"=>"ASC"), Array("IBLOCK_ID"=>6, "ACTIVE"=>"Y"), false, false ,array());
    $i = 0;
    while($ob = $res->Fetch()){
        $res2 = CIBlockElement::GetList(Array("PROPERTY_CITY.NAME"=>"ASC"), Array("IBLOCK_ID"=>7, "ACTIVE"=>"Y", "PROPERTY"=>array("CITY"=>$ob["ID"],"BRANDS"=>$ar_enum_list["ID"])));
        $i2 = 0;
        while($ob2 = $res2->Fetch()){
            $arCity[$i]["ID"] = $ob["ID"];
            $arCity[$i]["NAME"] = $ob["NAME"];
            $arCity[$i]["SERVICE"][$i2]["ID"] = $ob2["ID"];
            $arCity[$i]["SERVICE"][$i2]["NAME"] = $ob2["NAME"];
            $i2++;
        }
        $i++;
    }?>
    <div class="bordBlock-wrap custom-bordblock custom-bordblock-brand" id="brand<?=$ar_enum_list["ID"]?>">
        <div class="bordBlock_title">
            <a data-brand="<?=$ar_enum_list["ID"]?>" class="brand-button" href="#"><?=$ar_enum_list["VALUE"]?></a>
        </div>
        <div class="bordBlock">
            <?
            $APPLICATION->IncludeComponent("bitrix:main.include", ".default", array("AREA_FILE_SHOW"=>"file", "PATH"=>"/include/textbrand" . $ar_enum_list["ID"] . ".php", "EDIT_TEMPLATE"=>"standard.php"), false); ?>
            <form action="" method="post">
                <select data-brand="<?=$ar_enum_list["ID"]?>" class="select-city" name="region">
                    <option value="">Выбирите регион</option>
                    <?foreach($arCity as $city){?>
                        <option value="<?=$city["ID"]?>"><?=$city["NAME"]?></option>
                    <? } ?>
                </select>
                <?foreach($arCity as $city){?>
                 <select class="select-service" data-city="<?=$city["ID"]?>" name="service">
                    <option value="">Выбирите сервис-центр</option>
                    <?foreach($city["SERVICE"] as $service){?>
                        <option value="<?=$service["ID"]?>"><?=$service["NAME"]?></option>
                    <? } ?>
                </select>
                <? } ?>
            </form>
            <div class="text-wrapper"></div>
        </div>
    </div>
<? } ?>
</div>

<script>
    var curBrand = 0;
    $(function() {
        $(".brand-button").click(function() {
            var brand = $(this).data("brand");
            curBrand = brand;
            $('.select-city').css("display", "none");
            $('.select-service').css("display", "none");
            $('.text-wrapper').css("display", "none");
            $('#brand' + brand + ' .select-city').css("display", "block");
            return false;
        });

        $(".select-city").change(function() {
            var brand = $(this).data("brand");
            $('.select-service').css("display", "none");
            $('.text-wrapper').css("display", "none");
            if (!this.value)
                return false;

            var curService = this.value;
						var curBrand = $(this).data("brand");
            $.ajax({
                url : "/ajax/get-service.php",
                type : "POST",
                data : {
                    id : curService,
										brand: curBrand
										
                },
                success : function(data) {
                    //console.log(data);
                    $('#brand' + curBrand + ' .text-wrapper').html(data);

                    $('#brand' + curBrand + ' .text-wrapper').find("img").each(function() {
                        $(this).wrap('<a class="fancy-img" href="' + this.src + '"></a>');
                    });
                    $(".fancy-img").fancybox({
                        titleShow : false
                    });

                    $('#brand' + curBrand + ' .text-wrapper').css("display", "block");
                },
                error : function(xhr, textStatus, errorThrown) {
                    console.log(textStatus);
                }
            });

            //$('#brand'+brand+' select[data-city="'+this.value+'"]').css("display","block");
        });

        $(".select-service").change(function() {
            $('.text-wrapper').css("display", "none");
            $(".text-wrapper").html("");

            if (!this.value)
                return false;
            var curCity = $(this).data("city");
            var curService = this.value;

            $.ajax({
                url : "/ajax/get-service.php",
                type : "POST",
                data : {
                    id : curService
                },
                success : function(data) {
                    //console.log(data);
                    $('#brand' + curBrand + ' .text-wrapper').html(data);

                    $('#brand' + curBrand + ' .text-wrapper').find("img").each(function() {
                        $(this).wrap('<a class="fancy-img" href="' + this.src + '"></a>');
                    });
                    $(".fancy-img").fancybox({
                        titleShow : false
                    });

                    $('#brand' + curBrand + ' .text-wrapper').css("display", "block");
                },
                error : function(xhr, textStatus, errorThrown) {
                    console.log(textStatus);
                }
            });

        });
    })
</script>


<?$APPLICATION->IncludeComponent("bitrix:main.include",
    ".default",
    Array(
        "AREA_FILE_SHOW"=>"file",
        "PATH"=>"/include_area/region.php",
        "EDIT_TEMPLATE"=>"standard.php"),
    false
);
require ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>