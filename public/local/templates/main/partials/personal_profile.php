<? 
use App\View as v;
use Core\Util;
?>
<div class="profile">
    <div class="product-registration-edit form" <?= !v::isEmpty($error) ? 'style="display: none"' : '' ?>>
        <? if (!v::isEmpty($message)): ?>
            <div class="form__message form__message--success">
                <?= $message ?>
            </div>
        <? endif ?>
        <div class="top">
            <span>Контактные данные</span>
            <span class="edit-btn">редактировать данные</span>
        </div>
        <div class="bottom">
            <? foreach ($fields as $f): ?>
                <p class="line"><?= $f['value'] ?></p>
            <? endforeach ?>
            <p class="line" style="margin-top: 2em"><a href="javascript:void(0)" class="change-password-shortcut">Сменить пароль</a></p>
        </div>
    </div>
    <div class="product-registration-hidden" <?= !v::isEmpty($error) ? 'style="display: block"' : '' ?>>
        <form class="form validate" method="post" name="form1" action="" enctype="multipart/form-data">
            <? if (!v::isEmpty($error)): ?>
                <div class="form__message form__message--error">
                    <?= $error ?>
                </div>
            <? endif ?>
            <p class="title">Введите контактные данные</p>
            <? foreach ($fields as $f): ?>
                <input name="<?= $f['name'] ?>"
                       type="<?= $f['type'] ?>"
                       class="input"
                       placeholder="<?= $f['label'].':'.($f['required'] ? '*' : '') ?>"
                    <?= $f['required'] ? 'required' : '' ?>
                       value="<?= v::escAttr($f['value']) ?>">
            <? endforeach ?>
            <? $showBlock = !v::isEmpty(v::get($_REQUEST, 'NEW_PASSWORD')) ?>
            <span class="simple-btn change-password" <?= $showBlock ? 'style="display: none"' : '' ?>>Сменить пароль</span>
            <div class="change-password-hidden" <?= $showBlock ? 'style="display: block"' : '' ?>>
                <? $id = 'password-'.Util::uniqueId() ?>
                <input name="NEW_PASSWORD" id="<?= $id ?>" type="password" class="input" placeholder="Новый пароль">
                <input name="NEW_PASSWORD_CONFIRM" data-rule-equalto="<?= '#'.$id ?>" type="password" class="input" placeholder="Новый пароль еще раз">
            </div>
            <input name="save" type="submit" class="yellow-btn" value="Сохранить изменения">
        </form>
    </div>
</div>
