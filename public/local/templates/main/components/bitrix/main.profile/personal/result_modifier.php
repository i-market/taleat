<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Core\Underscore as _;

$keys = ['name', 'required', 'type', 'label'];
$fields = array_map(_::partial('array_combine', $keys), [
    ['LAST_NAME',       true,  'text',  'Фамилия'],
    ['NAME',            true,  'text',  'Имя'],
    ['SECOND_NAME',     false, 'text',  'Отчество'],
    ['EMAIL',           true,  'email', 'E-mail'],
    ['PERSONAL_PHONE',  true,  'tel',   'Телефон'],
    ['PERSONAL_ZIP',    true,  'text',  'Индекс'],
    ['PERSONAL_STATE',  true,  'text',  'Регион'],
    ['PERSONAL_CITY',   true,  'text',  'Город'],
    ['PERSONAL_STREET', true,  'text',  'Дом, корпус, квартира'],
]);
// from component.php
$allFields = [
    'TITLE',
    'NAME',
    'LAST_NAME',
    'SECOND_NAME',
    'EMAIL',
    'LOGIN',
    'PERSONAL_PROFESSION',
    'PERSONAL_WWW',
    'PERSONAL_ICQ',
    'PERSONAL_GENDER',
    'PERSONAL_BIRTHDAY',
    'PERSONAL_PHOTO',
    'PERSONAL_PHONE',
    'PERSONAL_FAX',
    'PERSONAL_MOBILE',
    'PERSONAL_PAGER',
    'PERSONAL_STREET',
    'PERSONAL_MAILBOX',
    'PERSONAL_CITY',
    'PERSONAL_STATE',
    'PERSONAL_ZIP',
    'PERSONAL_COUNTRY',
    'PERSONAL_NOTES',
    'WORK_COMPANY',
    'WORK_DEPARTMENT',
    'WORK_POSITION',
    'WORK_WWW',
    'WORK_PHONE',
    'WORK_FAX',
    'WORK_PAGER',
    'WORK_STREET',
    'WORK_MAILBOX',
    'WORK_CITY',
    'WORK_STATE',
    'WORK_ZIP',
    'WORK_COUNTRY',
    'WORK_PROFILE',
    'WORK_LOGO',
    'WORK_NOTES',
    'AUTO_TIME_ZONE',
];
$arResult['FIELDS'] = $fields;
$arResult['PRESERVE_FIELDS'] = array_filter(array_diff($allFields, _::pluck($fields, 'name')), function ($f) use ($arResult) {
    return !in_array(_::get($arResult, ['arUser', $f]), [null, ''], true);
});
