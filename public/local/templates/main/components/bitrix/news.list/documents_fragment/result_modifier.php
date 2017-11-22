<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bex\Tools\Iblock\IblockTools;
use App\Iblock;
use Core\Underscore as _;

$sections = _::keyBy('ID', iter\toArray(Iblock::iter(CIBlockSection::GetList([], [
    'IBLOCK_ID' => IblockTools::find(Iblock::PARTNER_TYPE, Iblock::DOCUMENTS)->id()
]))));
$arResult['SECTIONS'] = $arParams['PARENT_SECTION'] > 0
    ? _::pick($sections, [$arParams['PARENT_SECTION']])
    : $sections;
