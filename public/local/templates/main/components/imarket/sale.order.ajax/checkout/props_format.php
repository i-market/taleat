<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use App\User;
use App\View as v;
?>
<?
if (!function_exists("showFilePropertyField"))
{
	function showFilePropertyField($name, $property_fields, $values, $max_file_size_show=50000)
	{
		$res = "";

		if (!is_array($values) || empty($values))
			$values = array(
				"n0" => 0,
			);

		if ($property_fields["MULTIPLE"] == "N")
		{
			$res = "<input type=\"file\" size=\"".$max_file_size_show."\" value=\"".$property_fields["VALUE"]."\" name=\"".$name."[0]\" id=\"".$name."[0]\">";
		}
		else
		{
			$res = '
			<script type="text/javascript">
				function addControl(item)
				{
					var current_name = item.id.split("[")[0],
						current_id = item.id.split("[")[1].replace("[", "").replace("]", ""),
						next_id = parseInt(current_id) + 1;

					var newInput = document.createElement("input");
					newInput.type = "file";
					newInput.name = current_name + "[" + next_id + "]";
					newInput.id = current_name + "[" + next_id + "]";
					newInput.onchange = function() { addControl(this); };

					var br = document.createElement("br");
					var br2 = document.createElement("br");

					BX(item.id).parentNode.appendChild(br);
					BX(item.id).parentNode.appendChild(br2);
					BX(item.id).parentNode.appendChild(newInput);
				}
			</script>
			';

			$res .= "<input type=\"file\" size=\"".$max_file_size_show."\" value=\"".$property_fields["VALUE"]."\" name=\"".$name."[0]\" id=\"".$name."[0]\">";
			$res .= "<br/><br/>";
			$res .= "<input type=\"file\" size=\"".$max_file_size_show."\" value=\"".$property_fields["VALUE"]."\" name=\"".$name."[1]\" id=\"".$name."[1]\" onChange=\"javascript:addControl(this);\">";
		}

		return $res;
	}
}

if (!function_exists("PrintPropsForm"))
{
	function PrintPropsForm($arSource=Array(), $locationTemplate = ".default")
	{
	    global $USER;
		if (!empty($arSource))
		{
            $suggestions = User::userPropSuggestions($USER);
			foreach($arSource as $arProperties)
			{
				if($arProperties["SHOW_GROUP_NAME"] == "Y")
				{
					?>
                    <? // $arProperties["GROUP_NAME"] ?>
                    <?
				}
				?>
                <? $label = $arProperties["NAME"].':'.($arProperties["REQUIED_FORMATED"]=="Y" ? '*' : '') ?>
                <?
                if($arProperties["TYPE"] == "CHECKBOX")
                {
                    ?>
                    <input type="hidden" name="<?=$arProperties["FIELD_NAME"]?>" value="">
                    <input type="checkbox" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" value="Y"<?if ($arProperties["CHECKED"]=="Y") echo " checked";?>>
                    <?
                }
                elseif($arProperties["TYPE"] == "TEXT")
                {
                    $value = $arProperties["VALUE"];
                    // prefill with user data
                    if (v::isEmpty($value)) {
                        $value = v::get($suggestions, $arProperties['FIELD_ID'], '');
                    }
                    ?>
                    <input type="text" class="input" placeholder="<?= $label ?>" maxlength="250" size="<?=$arProperties["SIZE1"]?>" value="<?=$value?>" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>">
                    <?
                }
                elseif($arProperties["TYPE"] == "SELECT")
                {
                    ?>
                    <select name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" size="<?=$arProperties["SIZE1"]?>">
                    <?
                    foreach($arProperties["VARIANTS"] as $arVariants)
                    {
                        ?>
                        <option value="<?=$arVariants["VALUE"]?>"<?if ($arVariants["SELECTED"] == "Y") echo " selected";?>><?=$arVariants["NAME"]?></option>
                        <?
                    }
                    ?>
                    </select>
                    <?
                }
                elseif ($arProperties["TYPE"] == "MULTISELECT")
                {
                    ?>
                    <? // TODO ?>
                    <select multiple name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" size="<?=$arProperties["SIZE1"]?>">
                    <?
                    foreach($arProperties["VARIANTS"] as $arVariants)
                    {
                        ?>
                        <option value="<?=$arVariants["VALUE"]?>"<?if ($arVariants["SELECTED"] == "Y") echo " selected";?>><?=$arVariants["NAME"]?></option>
                        <?
                    }
                    ?>
                    </select>
                    <?
                }
                elseif ($arProperties["TYPE"] == "TEXTAREA")
                {
                    ?>
                    <div class="label_textarea">
                        <textarea placeholder="<?= $label ?>" rows="<?=$arProperties["SIZE2"]?>" cols="<?=$arProperties["SIZE1"]?>" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>"><?=$arProperties["VALUE"]?></textarea>
                    </div>
                    <?
                }
                elseif ($arProperties["TYPE"] == "LOCATION")
                {
                    $value = 0;
                    if (is_array($arProperties["VARIANTS"]) && count($arProperties["VARIANTS"]) > 0)
                    {
                        foreach ($arProperties["VARIANTS"] as $arVariant)
                        {
                            if ($arVariant["SELECTED"] == "Y")
                            {
                                $value = $arVariant["ID"];
                                break;
                            }
                        }
                    }

                    $GLOBALS["APPLICATION"]->IncludeComponent(
                        "bitrix:sale.ajax.locations",
                        $locationTemplate,
                        array(
                            "AJAX_CALL" => "N",
                            "COUNTRY_INPUT_NAME" => "COUNTRY",//.$arProperties["FIELD_NAME"],
                            "REGION_INPUT_NAME" => "REGION",//.$arProperties["FIELD_NAME"],
                            "CITY_INPUT_NAME" => $arProperties["FIELD_NAME"],
                            "CITY_OUT_LOCATION" => "Y",
                            "LOCATION_VALUE" => $value,
                            "ORDER_PROPS_ID" => $arProperties["ID"],
                            "ONCITYCHANGE" => ($arProperties["IS_LOCATION"] == "Y" || $arProperties["IS_LOCATION4TAX"] == "Y") ? "submitForm()" : "",
                            "SIZE1" => $arProperties["SIZE1"],
                        ),
                        null,
                        array('HIDE_ICONS' => 'Y')
                    );
                }
                elseif ($arProperties["TYPE"] == "RADIO")
                {
                    // TODO
                    foreach($arProperties["VARIANTS"] as $arVariants)
                    {
                        ?>
                        <input type="radio" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["VALUE"]?>" value="<?=$arVariants["VALUE"]?>"<?if($arVariants["CHECKED"] == "Y") echo " checked";?>> <label for="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["VALUE"]?>"><?=$arVariants["NAME"]?></label><br />
                        <?
                    }
                }
                elseif ($arProperties["TYPE"] == "FILE")
                {
                    // TODO
                    echo showFilePropertyField("ORDER_PROP_".$arProperties["ID"], $arProperties, $arProperties["VALUE"], $arProperties["SIZE1"]);
                }


                if (strlen($arProperties["DESCRIPTION"]) > 0)
                {
                    // TODO
                    ?><br /><small><?echo $arProperties["DESCRIPTION"] ?></small><?
                }
			}
			return true;
		}
		return false;
	}
}
?>