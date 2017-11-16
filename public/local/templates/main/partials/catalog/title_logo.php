<?
use App\View as v;
use App\Product;
?>

<? if (!v::isEmpty($picture)): ?>
    <div class="catalog-title-img">
        <img src="<?= v::resize($picture, ...Product::BRAND_LOGO) ?>"
             alt="<?= isset($title) ? $title : '' ?>">
    </div>
<? endif ?>
