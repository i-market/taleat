<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;

$showHiddenInputs = function () use ($arResult) {
    ?>
    <?=$arResult["BX_SESSION_CHECK"]?>
    <input type="hidden" name="lang" value="<?=LANG?>" />
    <input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
    <? // otherwise fields (passed to update) will contain nulls ?>
    <? foreach ($arResult['PRESERVE_FIELDS'] as $field): ?>
        <input type="hidden" name="<?= $field ?>" value="<?= $arResult['arUser'][$field] ?>">
    <? endforeach ?>
    <?
};
?>
<? $error = $arResult['strProfileError'] ?>
<div class="data-center">
    <div class="product-registration-edit form" <?= !v::isEmpty($error) ? 'style="display: none"' : '' ?>>
        <? if ($arResult['DATA_SAVED'] === 'Y'): ?>
            <div class="form__message form__message--success">
                Ваши изменения были сохранены
            </div>
        <? endif ?>
        <div class="top">
            <span>Контактные данные</span>
            <span class="edit-btn">редактировать данные</span>
        </div>
        <div class="bottom">
            <p class="line"><?= $arResult['arUser']['LAST_NAME'] ?></p>
            <p class="line"><?= $arResult['arUser']['NAME'] ?></p>
            <p class="line"><?= $arResult['arUser']['SECOND_NAME'] ?></p>
            <p class="line"><?= $arResult['arUser']['WORK_COMPANY'] ?></p>
            <p class="line"><?= $arResult['arUser']['WORK_CITY'] ?></p>
            <? // TODO phone link ?>
            <p class="TODO-mockup line"><a href="tel:+7(495) 450-45-38"><?= $arResult['arUser']['WORK_PHONE'] ?></a></p>
            <p class="line"><a href="<?= 'mailto:'.$arResult['arUser']['EMAIL'] ?>"><?= $arResult['arUser']['EMAIL'] ?></a></p>
        </div>
    </div>
    <div class="product-registration-hidden" <?= !v::isEmpty($error) ? 'style="display: block"' : '' ?>>
        <form class="form validate" method="post" name="form1" action="<?=$arResult["FORM_TARGET"]?>" enctype="multipart/form-data">
            <? if (!v::isEmpty($error)): ?>
                <div class="form__message form__message--error">
                    <?= $error ?>
                </div>
            <? endif ?>
            <p class="title">Введите контактные данные</p>
            <? $showHiddenInputs() ?>
            <? // TODO validate required fields ?>
            <? foreach ($arResult['FIELDS'] as $f): ?>
                <input name="<?= $f['name'] ?>"
                       type="<?= $f['type'] ?>"
                       class="input"
                       placeholder="<?= $f['label'].':'.($f['required'] ? '*' : '') ?>"
                       <?= $f['required'] ? 'required' : '' ?>
                       value="<?= $arResult['arUser'][$f['name']] ?>">
            <? endforeach ?>
            <input name="save" type="submit" class="yellow-btn" value="Сохранить изменения">
        </form>
    </div>
    <div class="TODO-mockup">
        <div class="wrap-checkbox">
            <input type="checkbox" hidden="hidden" id="1">
            <label for="1">Подписка на новости</label>
        </div>
        <span class="simple-btn change-password">Сменить пароль</span>
        <div class="change-password-hidden">
            <input type="password" class="input" placeholder="Старый пароль">
            <input type="password" class="input" placeholder="Новый пароль">
            <input type="password" class="input" placeholder="Новый пароль еще раз">
            <button class="yellow-btn">Сохранить изменения</button>
        </div>
    </div>
</div>
