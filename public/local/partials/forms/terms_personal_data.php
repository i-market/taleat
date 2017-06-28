<? $isInitial = $_SERVER['REQUEST_METHOD'] === 'GET' ?>

<? // referenced in /bitrix/templates/main_page/components/imarket/sale.order.ajax/visual/template.php ?>
<? $key = 'AGREED_PERSONAL_DATA' ?>

<input id="personal-data" name="<?= $key ?>" type="checkbox"<?= isset($_REQUEST[$key]) || $isInitial ? ' checked' : '' ?>>
<label for="personal-data">
    Я соглашаюсь на <a href="<?= SITE_DIR.'terms/privacy/' ?>" target="_blank">обработку персональных данных</a>
</label>
