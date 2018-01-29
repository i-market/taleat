<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
use App\Product;
?>
<section class="wrap-labels-slider wrap-slider section">
    <div class="wrap wrap--wp">
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
        <div class="labels-slider slider">
            <?
            $babyliss = 'katalog1';
            // spacing between logos looks best in this order
            $order = ['kenwood', 'braun', $babyliss, 'delonghi', 'moser'];
            ?>
            <? foreach ($arResult['SORT']($arResult['SECTIONS'], $order) as $section): ?>
                <? if (!v::isEmpty($section['PICTURE'])): ?>
                    <div class="slide" id="<?= v::addEditingActions($section, $this) ?>">
                        <a href="<?= Product::sectionUrl($section) ?>">
                            <img src="<?= $section['PICTURE']['SRC'] ?>" alt="<?= $section['NAME'] ?>" />
                        </a>
                    </div>
                <? endif ?>
            <? endforeach ?>
        </div>
    </div>
</section>
