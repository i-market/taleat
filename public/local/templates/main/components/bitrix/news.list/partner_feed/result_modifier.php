<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\Partner;
use Core\Underscore as _;

$sections = _::keyBy('ID', Partner::feedSections($USER));
foreach ($arResult['ITEMS'] as &$itemRef) {
    $itemRef['SECTION'] = _::get($sections, $itemRef['IBLOCK_SECTION_ID']);
}
