<? use App\View as v; ?>

<form action="?action=import" method="post" class="form form--simple-upload validate" enctype="multipart/form-data">
    <? if (!v::isEmpty($result)): ?>
        <div class="form__message form__message--success">
            Количество обновленных товаров: <?= $result['changedCount'] ?>
        </div>
    <? endif ?>
    <label class="simple-btn">
        Выбрать файл...
        <input class="file"
               type="file"
               name="file"
               style="display: none"
               required
               data-msg-required="Пожалуйста, выберите файл."
               data-error-container="#file-error">
    </label>
    <div id="file-error"></div>
    <div class="filename"></div>
    <button type="submit">Импортировать</button>
</form>