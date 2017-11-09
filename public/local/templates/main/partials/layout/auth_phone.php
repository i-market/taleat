<? global $USER ?>

<? if ($USER->IsAuthorized()): ?>
    <div class="menu-hidden-registered">
        <p class="name"><?= $USER->GetLogin() ?></p>
        <p class="link"><a href="<?= $auth['profileLink'] ?>">Личный кабинет</a></p>
        <p class="link"><a href="<?= $auth['logoutLink'] ?>">Выход</a></p>
    </div>
<? else: ?>
    <? // TODO .hidden? see mockup ?>
    <div class="menu-hidden-sign-in">
        <a href="<?= $auth['loginLink'] ?>">Вход</a>
        <span>/</span>
        <a data-modal="register-modal" href="<?= $auth['registerLink'] ?>">Регистрация</a>
    </div>
<? endif ?>