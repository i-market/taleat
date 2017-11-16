<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\Iblock;
use Core\Underscore as _;

$brand = CIBlockSection::GetNavChain($arResult['IBLOCK_ID'], $arResult['ID'])->GetNext();
$arResult['BRAND'] = _::update($brand, 'PICTURE', [CFile::class, 'GetFileArray']);

