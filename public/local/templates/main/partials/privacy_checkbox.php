<?
use App\View as v;
use Core\Util;
?>
<? $id = 'input-'.Util::uniqueId() ?>
<input class="checkbox"
       type="checkbox"
       name="privacy_policy"
       value="1"
       data-rule-required="true" <? // html5 spec: hidden inputs can't be `required` ?>
       data-msg-required="<?= v::privacyPolicyError() ?>"
       <?
       // https://stackoverflow.com/q/931687
       // jquery validation doesn't support multiple inputs with the same name
       ?>
       <? if (isset($errorContainer)): ?>
           data-error-container="<?= $errorContainer ?>"
       <? endif ?>
       checked
       hidden="hidden"
       id="<?= $id ?>">
<label for="<?= $id ?>">Даю согласие на <a href="<?= v::path('terms/privacy') ?>" target="_blank">обработку персональных данных</a></label>
