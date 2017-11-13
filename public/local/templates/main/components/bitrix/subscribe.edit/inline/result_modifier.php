<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Core\Underscore as _;
use App\App;

$rubric = _::find($arResult['RUBRICS'], function ($rubric) {
    return $rubric['ID'] == App::NEWSLETTER_ID;
});
if (!$arResult['SUBSCRIPTION']) {
    $state = 'no_subscription'; // initial state
} elseif ($arResult['SUBSCRIPTION']['ACTIVE'] === 'N') {
    $state = 'did_unsubscribe'; // deactivated sub, but rubric is still checked
} elseif ($arResult['SUBSCRIPTION']['CONFIRMED'] === 'N') {
    $state = 'awaiting_confirmation';
} elseif ($rubric['CHECKED']) {
    $state = 'subscribed';
} else {
    $state = 'no_subscription';
    App::getInstance()->assert(false, 'illegal state');
}
$arResult['RUBRIC'] = $rubric;
$arResult['STATE'] = $state;