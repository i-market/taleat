<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
use App\View\FormMacros as m;
use Core\Util;
use App\Auth;

$showHiddenInputs = function () use ($arResult) {
    // main.register requires non-empty login
    ?>
    <input type="hidden" name="REGISTER[LOGIN]" value="<?= Auth::LOGIN_EQ_EMAIL ?>" />
    <? if (strlen($arResult["BACKURL"]) > 0): ?>
        <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
    <? endif ?>
    <?
};
$showErrorMessage = function () use ($arResult) {
    if (v::isEmpty($arResult["ERRORS"])) return;
    foreach ($arResult["ERRORS"] as $key => $error)
        if (intval($key) == 0 && $key !== 0)
            $arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;".GetMessage("REGISTER_FIELD_".$key)."&quot;", $error);
    $message = implode("<br />", $arResult["ERRORS"]);
    ?>
    <div class="form__message form__message--error">
        <?= $message ?>
    </div>
    <?
};
?>
<? $showErrorMessage() ?>
<form class="validate" method="post" action="<?=POST_FORM_ACTION_URI?>" name="regform" enctype="multipart/form-data">
    <? $showHiddenInputs() ?>
    <? if ($arParams['TYPE'] === 'customer'): ?>
        <input type="hidden" name="user_type" value="customer">
        <? m::showInput('REGISTER[LAST_NAME]', 'Фамилия *', ['required' => true]) ?>
        <? m::showInput('REGISTER[NAME]', 'Имя *', ['required' => true]) ?>
        <? m::showInput('REGISTER[SECOND_NAME]', 'Отчество') ?>
        <? m::showInput('REGISTER[EMAIL]', 'E-mail *', ['required' => true, 'type' => 'email']) ?>
        <? m::showInput('REGISTER[PASSWORD]', 'Пароль', ['required' => true, 'type' => 'password']) ?>
        <? m::showInput('REGISTER[CONFIRM_PASSWORD]', 'Повторите пароль', ['required' => true, 'type' => 'password']) ?>
    <? elseif ($arParams['TYPE'] === 'service-center'): ?>
        <input type="hidden" name="user_type" value="service-center">
        <? m::showInput('REGISTER[LAST_NAME]', 'Фамилия *', ['required' => true]) ?>
        <? m::showInput('REGISTER[NAME]', 'Имя *', ['required' => true]) ?>
        <? m::showInput('REGISTER[SECOND_NAME]', 'Отчество') ?>
        <? m::showInput('REGISTER[WORK_COMPANY]', 'Компания *', ['required' => true]) ?>
        <? m::showInput('REGISTER[WORK_CITY]', 'Город *', ['required' => true]) ?>
        <? m::showInput('REGISTER[WORK_PHONE]', 'Телефон *', ['required' => true, 'type' => 'tel']) ?>
        <? m::showInput('REGISTER[EMAIL]', 'E-mail *', ['required' => true, 'type' => 'email']) ?>
        <? m::showInput('REGISTER[PASSWORD]', 'Пароль', ['required' => true, 'type' => 'password']) ?>
        <? m::showInput('REGISTER[CONFIRM_PASSWORD]', 'Повторите пароль', ['required' => true, 'type' => 'password']) ?>
    <? endif ?>
    <div class="wrap-checkbox">
        <? $id = 'error-'.\Core\Util::uniqueId() ?>
        <?= v::render('partials/privacy_checkbox.php', ['errorContainer' => '#'.$id]) ?>
    </div>
    <div id="<?= $id ?>"></div>
    <input type="submit" name="register_submit_button" class="download-btn" value="Зарегистрироваться">
</form>
