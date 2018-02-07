<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\Partner;
use Core\Underscore as _;

$sections = _::keyBy('ID', Partner::documentSections($USER));
$arResult['SECTIONS'] = $arParams['PARENT_SECTION'] > 0
    ? _::pick($sections, [$arParams['PARENT_SECTION']])
    : $sections;
