<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\App;
use App\View as v;
use Bitrix\Main\Application;
use Bitrix\Main\Web\Uri;

$APPLICATION->SetPageProperty('layout', 'bare');

// beware: `user_type` is used in `App\EventHandlers::onAfterUserRegister`
$userType = v::get($_REQUEST, 'user_type', 'customer');

if (!in_array($userType, ['customer', v::PARTNER_SIGNUP_TYPE])) {
    App::getInstance()->assert(false, 'illegal state');
}

if ($userType === 'customer') {
    $required = [
        'LAST_NAME',
        'NAME',
        'EMAIL',
    ];
    $optional = [
        'SECOND_NAME'
    ];
} elseif ($userType === v::PARTNER_SIGNUP_TYPE) {
    $required = [
        'LAST_NAME',
        'NAME',
        'WORK_COMPANY',
        'WORK_CITY',
        'WORK_PHONE',
        'EMAIL',
    ];
    $optional = [
        'SECOND_NAME'
    ];
} else {
    $required = [];
    $optional = [];
}
$withParams = function ($params) {
    $uri = Application::getInstance()->getContext()->getRequest()->getRequestUri();
    return (new Uri($uri))->addParams($params)->getUri();
};
?>
<div class="modal-like form signup-form">
    <div class="block">
        <div class="title">Регистрация</div>
        <div class="modal-tab-links">
            <? $tabs = ['customer' => 'Я покупатель', v::PARTNER_SIGNUP_TYPE => 'Сервисный центр'] ?>
            <? foreach ($tabs as $type => $text): ?>
                <? $class = $userType === $type ? 'active' : '' ?>
                <a href="<?= $withParams(['user_type' => $type]) ?>" class="tab-link <?= $class ?>"><?= $text ?></a>
            <? endforeach ?>
        </div>
        <div class="tab_blocks">
            <div>
                <? $APPLICATION->IncludeComponent(
                    "bitrix:main.register",
                    "signup",
                    Array(
                        "AUTH" => "Y",
                        "REQUIRED_FIELDS" => $required,
                        "SET_TITLE" => "N",
                        "SHOW_FIELDS" => array_merge($required, $optional),
                        "SUCCESS_PAGE" => "",
                        "USER_PROPERTY" => array(),
                        "USER_PROPERTY_NAME" => "",
                        "USE_BACKURL" => "Y",
                        "TYPE" => $userType
                    )
                ); ?>
            </div>
        </div>
    </div>
</div>
