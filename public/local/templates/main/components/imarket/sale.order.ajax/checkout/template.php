<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use App\App;
use App\View as v;
use Core\Util;
use App\Product;

$showEverything = false && App::env() === \Core\Env::DEV; // for debugging

$showWrapper = function ($fragment) use ($arResult, &$APPLICATION) {
    ?>
    <? if ($fragment === 'header'): ?>
        <div class="product-registration-info">
            <form class="inner form" action="" method="POST" name="ORDER_FORM" id="ORDER_FORM" enctype="multipart/form-data">
                <input type="hidden" name="confirmorder" id="confirmorder" value="Y">
                <input type="hidden" name="profile_change" id="profile_change" value="N">
                <input type="hidden" name="is_ajax_post" id="is_ajax_post" value="Y">
                <?= bitrix_sessid_post() ?>
                <div class="editable-area paragraph">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => v::includedArea('personal/order/checkout_text.php')
                        )
                    ); ?>
                </div>
                <div id="order_form_content">
                    <? // ajax response goes here ?>
    <? elseif ($fragment === 'footer'): ?>
                </div>
                <div class="wrap-table">
                    <table>
                        <tr>
                            <? $quantity = count($arResult['BASKET_ITEMS']) ?>
                            <td><?= $quantity.' '.Util::units($quantity, 'товар', 'товара', 'товаров') ?>, на сумму:</td>
                            <td><?= Product::wrapAmount($arResult['ORDER_PRICE_FORMATED']) ?></td>
                        </tr>
                        <tr>
                            <td>Доставка:</td>
                            <td><?= Product::wrapAmount($arResult['DELIVERY_PRICE_FORMATED']) ?></td>
                        </tr>
                    </table>
                    <div class="total">
                        <span class="text">Итого к оплате:</span>
                        <span class="price"><?= Product::wrapCurrency($arResult['ORDER_TOTAL_PRICE_FORMATED'], true) ?></span>
                    </div>
                </div>
                <input type="button" class="yellow-btn" name="submitbutton" onClick="submitForm('Y');" value="Подтвердить заказ">
            </form>
        </div>
    <? endif ?>
    <?
};
?>
<NOSCRIPT>
	<div class="errortext"><?=GetMessage("SOA_NO_JS")?></div>
</NOSCRIPT>
<?
if(!$USER->IsAuthorized() && $arParams["ALLOW_AUTO_REGISTER"] == "N")
{
    // TODO messages
	if(!empty($arResult["ERROR"]))
	{
		foreach($arResult["ERROR"] as $v)
			echo ShowError($v);
	}
	elseif(!empty($arResult["OK_MESSAGE"]))
	{
		foreach($arResult["OK_MESSAGE"] as $v)
			echo ShowNote($v);
	}

	// TODO
	include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/auth.php");
}
else
{
	if($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y")
	{
		if(strlen($arResult["REDIRECT_URL"]) > 0)
		{
			?>
			<script type="text/javascript">
			window.top.location.href='<?=CUtil::JSEscape($arResult["REDIRECT_URL"])?>';
			</script>
			<?
			die();
		}
		else
		{
            // TODO
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/confirm.php");
		}
	}
	else
	{
		?>
        <script type="text/javascript">
          BX.addCustomEvent('onAjaxSuccess', function () {
            App.initComponents($('#order_form_content'))
          });

          function submitForm(val)
          {
            if(val != 'Y')
              BX('confirmorder').value = 'N';

            var orderForm = BX('ORDER_FORM');

            BX.ajax.submitComponentForm(orderForm, 'order_form_content', true);
            BX.submit(orderForm);

            return true;
          }
          function SetContact(profileId)
          {
            BX("profile_change").value = "Y";
            submitForm();
          }
        </script>
		<?if($_POST["is_ajax_post"] != "Y")
        {
            $showWrapper('header');
        }
		else
		{
			$APPLICATION->RestartBuffer();
		}
		if(!empty($arResult["ERROR"]) && $arResult["USER_VALS"]["FINAL_STEP"] == "Y")
		{
		    ?>
            <div class="form__message form__message--error">
                <?= join('<br>', $arResult["ERROR"]) ?>
            </div>
			<script type="text/javascript">
				top.BX.scrollToNode(top.BX('ORDER_FORM'));
			</script>
			<?
		}

		if (count($arResult["PERSON_TYPE"]) > 1 || $showEverything)
		{
			?>

            <p class="title"><?=GetMessage("SOA_TEMPL_PERSON_TYPE")?></p>
            <div class="wrap-btn">
                <?
                foreach($arResult["PERSON_TYPE"] as $v)
                {
                    ?>
                    <input style="display: none" type="radio" id="PERSON_TYPE_<?= $v["ID"] ?>" name="PERSON_TYPE" value="<?= $v["ID"] ?>"<?if ($v["CHECKED"]=="Y") echo " checked=\"checked\"";?> onClick="submitForm()">
                    <label class="simple-btn" for="PERSON_TYPE_<?= $v["ID"] ?>"><?= $v["NAME"] ?></label>
                    <?
                }
                ?>
            </div>
			<input type="hidden" name="PERSON_TYPE_OLD" value="<?=$arResult["USER_VALS"]["PERSON_TYPE_ID"]?>">
			<?
		}
		else
		{
			if(IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"]) > 0)
			{
				?>
				<input type="hidden" name="PERSON_TYPE" value="<?=IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"])?>">
				<input type="hidden" name="PERSON_TYPE_OLD" value="<?=IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"])?>">
				<?
			}
			else
			{
				foreach($arResult["PERSON_TYPE"] as $v)
				{
					?>
					<input type="hidden" id="PERSON_TYPE" name="PERSON_TYPE" value="<?=$v["ID"]?>">
					<input type="hidden" name="PERSON_TYPE_OLD" value="<?=$v["ID"]?>">
					<?
				}
			}
		}

		include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props.php");
		?>
        <?/*
        <? // TODO validate ?>
        <div class="wrap-checkbox">
            <? $id = 'legal-'.\Core\Util::uniqueId() ?>
            <? // TODO error: An invalid form control with name='legal-agreed' is not focusable. ?>
            <input type="checkbox" hidden="hidden" id="<?= $id ?>" name="legal-agreed" required>
            <label for="<?= $id ?>">Согласен на обработку <a href="<?= v::path('terms/privacy') ?>" target="_blank">персональных данных</a></label>
        </div>
        */?>
		<?
		if ($arParams["DELIVERY_TO_PAYSYSTEM"] == "p2d")
		{
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php");
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery.php");
		}
		else
		{
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery.php");
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php");
		}

		// TODO
		include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/related_props.php");
		?>
		<?
        $this->SetViewTarget('bitrix:sale.order.ajax/checkout/summary');
		include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/summary.php");
		$this->EndViewTarget();

		if(strlen($arResult["PREPAY_ADIT_FIELDS"]) > 0)
			echo $arResult["PREPAY_ADIT_FIELDS"];
		?>
		<?if($_POST["is_ajax_post"] != "Y")
		{
		    $showWrapper('footer');

			if($arParams["DELIVERY_NO_AJAX"] == "N")
			{
				$APPLICATION->AddHeadScript("/bitrix/js/main/cphttprequest.js");
				$APPLICATION->AddHeadScript("/bitrix/components/bitrix/sale.ajax.delivery.calculator/templates/.default/proceed.js");
			}
		}
		else
		{
			?>
			<script type="text/javascript">
				top.BX('confirmorder').value = 'Y';
				top.BX('profile_change').value = 'N';
			</script>
			<?
			die();
		}
	}
}
?>

