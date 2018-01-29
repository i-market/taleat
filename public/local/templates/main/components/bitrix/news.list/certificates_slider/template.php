<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;

?>
<? if (!v::isEmpty($arResult['ITEMS'])): ?>
    <section class="wrap-sertificate-slider wrap-slider section">
        <div class="section-title">
            <div class="wrap">
                <div class="section-title-block">
                    <h2>Наши сертификаты</h2>
                </div>
                <div class="dots"></div>
            </div>
        </div>
        <div class="wrap">
        <span class="arrows prev"><svg xmlns="http://www.w3.org/2000/svg" width="11" height="18" viewBox="0 0 11 18">
  <defs>
    <style>
      .cls-1 {
          fill: #214385;
          fill-rule: evenodd;
      }
    </style>
  </defs>
  <path id="arrow-left.svg" class="cls-1" d="M313,660l9-9h2l-9,9h-2Zm0,0,9,9h2l-9-9h-2Z" transform="translate(-313 -651)"/>
</svg>
</span>
            <span class="arrows next">
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

        </span>
            <div class="sertificate-slider slider">
                <? foreach ($arResult['ITEMS'] as $item): ?>
                    <a href="<?= $item['DISPLAY_PROPERTIES']['FILE']['FILE_VALUE']['SRC'] ?>"
                       class="sertificate-item"
                       data-fancybox="certificates"
                       id="<?= v::addEditingActions($item, $this) ?>">
                        <div class="img">
                            <img src="<?= $item['PREVIEW_PICTURE']['SRC'] ?>" alt="<?= $item['PREVIEW_PICTURE']['ALT'] ?>">
                        </div>
                        <p class="name"><?= $item['NAME'] ?></p>
                    </a>
                <? endforeach ?>
            </div>
        </div>
    </section>
<? endif ?>