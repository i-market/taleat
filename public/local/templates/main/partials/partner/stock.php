<?
use App\View as v;
use Core\Util;

global $APPLICATION;
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
            <? $files = [
                // uploaded by a third-party
                'Braun'     => '/partneram/ostatki_V/ostatki_braun1.xls',
                "De'Longhi" => '/partneram/ostatki_V/ostatki_braun2.xls',
                'Babyliss'  => '/partneram/ostatki_V/ostatki_babyliss.xls'
            ] ?>
            <? foreach ($files as $brand => $path): ?>
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