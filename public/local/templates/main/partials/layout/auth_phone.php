<?
use App\View as v;
use App\Auth;

global $USER;
?>
<? if ($USER->IsAuthorized()): ?>
    <div class="menu-hidden-registered">
        <p class="name"><?= $USER->GetLogin() ?></p>
        <p class="link"><a href="<?= $auth['profileLink'] ?>">Личный кабинет</a></p>
        <? if (Auth::hasAdminPanelAccess($USER)): ?>
            <p class="link"><a href="<?= v::path('admin') ?>">Панель администратора</a></p>
        <? endif ?>
        <? if (Auth::isPartner($USER)): ?>
            <p class="link"><a href="<?= v::path('partneram') ?>">Кабинет дилера</a></p>
        <? endif ?>
        <p class="link"><a href="<?= $auth['logoutLink'] ?>">Выход</a></p>
    </div>
<? else: ?>
    <? // TODO .hidden? see mockup ?>
    <div class="menu-hidden-sign-in">
        <a href="<?= $auth['loginLink'] ?>">Вход</a>
        <span>/</span>
        <a href="<?= $auth['registerLink'] ?>">Регистрация</a>
    </div>
<? endif ?>