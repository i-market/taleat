<?
use App\Partner;
use App\View as v;
use Core\Util;

global $APPLICATION, $USER;
?>
<div class="stock">
    <div class="tabs-inner">
        <div class="stock-balance">
            <div class="editable-area text">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => v::includedArea('partner/stock/text.php')
                    )
                ); ?>
            </div>
            <? foreach (Partner::stockFiles($USER) as $brand => list($_, $path)): ?>
                <? list($_, $ext) = Util::splitFileExtension($path) ?>
                <p class="line">
                    <a class="download-btn"
                        <?= v::attrs(v::docLinkAttrs($ext)) ?>
                       href="<?= $path ?>">Скачать остатки<span><?= $brand ?></span></a>
                </p>
            <? endforeach ?>
        </div>
    </div>
</div>