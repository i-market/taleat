<?
use App\View as v;
use App\Auth;

global $USER;
?>
<? if ($USER->IsAuthorized()): ?>
    <div class="rigistered">
        <span class="name"><?= $USER->GetLogin() ?></span>
        <ul class="dd_rigistered">
            <? if (Auth::isPartner($USER) || Auth::isUnconfirmedPartner($USER)): ?>
                <li><a href="<?= v::path('partneram') ?>">Кабинет дилера</a></li>
            <? else: ?>
                <li><a href="<?= $auth['profileLink'] ?>">Личный кабинет</a></li>
            <? endif ?>
            <? if (Auth::hasAdminPanelAccess($USER)): ?>
                <li><a href="<?= v::path('admin') ?>">Панель админа</a></li>
            <? endif ?>
            <li><a data-modal="contact-modal" href="javascript:void(0)">Написать сообщение</a></li>
            <li><a href="<?= $auth['logoutLink'] ?>">Выход</a></li>
        </ul>
    </div>
<? else: ?>
    <div class="sig-in">
        <a class="sig-in-link" href="<?= $auth['loginLink'] ?>">Вход</a>
        <span class="separator">/</span>
        <a class="sig-in-link" href="<?= $auth['registerLink'] ?>">Регистрация</a>
    </div>
<? endif ?>
