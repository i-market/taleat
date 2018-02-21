<?
use App\View as v;
use App\Auth;

global $USER;
?>
<? if ($USER->IsAuthorized()): ?>
    <div class="menu-hidden-registered">
        <p class="name"><?= $USER->GetLogin() ?></p>
        <? if (Auth::isPartner($USER) || Auth::isUnconfirmedPartner($USER)): ?>
            <p class="link"><a href="<?= v::path('partneram') ?>">Кабинет дилера</a></p>
        <? else: ?>
            <p class="link"><a href="<?= $auth['profileLink'] ?>">Личный кабинет</a></p>
        <? endif ?>
        <? if (Auth::hasAdminPanelAccess($USER)): ?>
            <p class="link"><a href="<?= v::path('admin') ?>">Панель админа</a></p>
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