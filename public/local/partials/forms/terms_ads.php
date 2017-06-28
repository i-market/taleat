<? $isInitial = $_SERVER['REQUEST_METHOD'] === 'GET' ?>
<? $key = 'AGREED_ADS' ?>
<input id="ads" name="<?= $key ?>" type="checkbox"<?= isset($_REQUEST[$key]) || $isInitial ? ' checked' : '' ?>>
<label for="ads">
    Я соглашаюсь на <a href="<?= SITE_DIR.'terms/privacy/' ?>" target="_blank">получение рекламы</a>
</label>
