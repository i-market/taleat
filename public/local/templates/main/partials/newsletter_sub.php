<?
use App\App;
use App\View as v;
use Core\Util;
?>
<div class="newsletter-sub">
    <? if ($state === 'awaiting_confirmation'): ?>
        <form class="confirmation form" action="" method="post">
            <? foreach ($errors as $msg): ?>
                <div class="form__message form__message--error">
                    <?= $msg ?>
                </div>
            <? endforeach ?>
            <div class="form__title">Подтверждение подписки</div>
            <input type="hidden" name="action" value="confirm">
            <input type="text" name="CONFIRM_CODE" value="<?= v::get($_REQUEST, 'CONFIRM_CODE') ?>" placeholder="Код подтверждения" />
            <input type="submit" class="submit" name="confirm" value="Подтвердить" />
        </form>
    <? else: ?>
        <form class="form" action="" method="post">
            <? foreach ($errors as $msg): ?>
                <div class="form__message form__message--error">
                    <?= $msg ?>
                </div>
            <? endforeach ?>
            <div class="wrap-checkbox">
                <? $id = 'checkbox-'.Util::uniqueId() ?>
                <? if ($state === 'no_subscription'): ?>
                    <input type="hidden" name="action" value="subscribe">
                    <input type="checkbox"
                           hidden="hidden"
                           class="toggle"
                           id="<?= $id ?>">
                <? elseif ($state === 'rubric_not_checked'): ?>
                    <input type="hidden" name="action" value="check_rubric">
                    <input type="checkbox"
                           hidden="hidden"
                           class="toggle"
                           id="<?= $id ?>">
                <? elseif ($state === 'rubric_checked'): ?>
                    <input type="hidden" name="action" value="uncheck_rubric">
                    <input type="checkbox"
                           checked
                           hidden="hidden"
                           class="toggle"
                           id="<?= $id ?>">
                <? endif ?>
                <label for="<?= $id ?>">Подписка на новости</label>
            </div>
        </form>
    <? endif ?>
</div>
