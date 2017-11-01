<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (!$arResult['NavShowAlways']) {
    if ($arResult['NavRecordCount'] == 0 || ($arResult['NavPageCount'] == 1 && $arResult['NavShowAll'] == false)) {
        return;
    }
}
?>
<div class="paginator">
    <div class="wrap">
        <div class="paginator-inner">
            <? if ($arResult['NAV']['PAGE_NUMBER'] > 1): ?>
                <? $prevNum = $arResult['NAV']['PAGE_NUMBER'] - 1 ?>
                <a class="prev" href="<?= $arResult['NAV']['URL']['SOME_PAGE'][$prevNum] ?>"><svg xmlns="http://www.w3.org/2000/svg" width="11" height="18" viewBox="0 0 11 18">
                        <defs>
                            <style>
                                .cls-1 {
                                    fill: #214385;
                                    fill-rule: evenodd;
                                }
                            </style>
                        </defs>
                        <path id="arrow-left.svg" class="cls-1" d="M313,660l9-9h2l-9,9h-2Zm0,0,9,9h2l-9-9h-2Z" transform="translate(-313 -651)"/>
                    </svg></a>
            <? endif ?>
            <ul>
                <? for ($PAGE_NUMBER=$arResult['NAV']['START_PAGE']; $PAGE_NUMBER<=$arResult['NAV']['END_PAGE']; $PAGE_NUMBER++):?>
                    <? if ($PAGE_NUMBER == $arResult['NAV']['PAGE_NUMBER']):?>
                        <li><a href="" class="active"><?= $PAGE_NUMBER ?></a></li>
                    <? else:?>
                        <li><a href="<?= $arResult['NAV']['URL']['SOME_PAGE'][$PAGE_NUMBER]?>"><?= $PAGE_NUMBER ?></a></li>
                    <? endif ?>
                <? endfor ?>
            </ul>
            <?
            $isLastPageActive = $arResult['NAV']['PAGE_NUMBER'] == $arResult['NAV']['END_PAGE'];
            if (!$isLastPageActive):
                $nextNum = $arResult['NAV']['PAGE_NUMBER'] + 1;
                ?>
                <a class="next" href="<?= $arResult['NAV']['URL']['SOME_PAGE'][$nextNum] ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="18" viewBox="0 0 11 18">
                        <defs>
                            <style>
                                .cls-1 {
                                    fill: #214385;
                                    fill-rule: evenodd;
                                }
                            </style>
                        </defs>
                        <path id="arrow-right.svg" class="cls-1" d="M1607,660l-9,9h-2l9-9h2Zm0,0-9-9h-2l9,9h2Z" transform="translate(-1596 -651)"/>
                    </svg>
                </a>
            <? endif ?>
        </div>
    </div>
</div>