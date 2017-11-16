<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Core\Underscore as _;

$brand = CIBlockSection::GetNavChain($arResult['SECTION']['IBLOCK_ID'], $arResult['SECTION']['ID'])->GetNext();
$arResult['BRAND'] = _::update($brand, 'PICTURE', [CFile::class, 'GetFileArray']);
