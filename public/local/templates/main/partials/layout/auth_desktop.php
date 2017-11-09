<? global $USER ?>

<? if ($USER->IsAuthorized()): ?>
    <div class="rigistered">
        <span class="name"><?= $USER->GetLogin() ?></span>
        <ul class="dd_rigistered">
            <li><a href="<?= $auth['profileLink'] ?>">Личный кабинет</a></li>
            <? // TODO links ?>
            <li class="TODO-mockup"><a href="#">Написать сообщение</a></li>
            <li class="TODO-mockup"><a href="#">Сменить пароль</a></li>
            <li><a href="<?= $auth['logoutLink'] ?>">Выход</a></li>
        </ul>
    </div>
<? else: ?>
    <? // TODO style=display:none? see mockup ?>
    <div class="sig-in">
        <a class="sig-in-link" href="<?= $auth['loginLink'] ?>">Вход</a>
        <span class="separator">/</span>
        <a data-modal="register-modal" class="sig-in-link" href="<?= $auth['registerLink'] ?>">Регистрация</a>
    </div>
<? endif ?>
