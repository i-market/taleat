<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\Videos;
use App\View as v;
?>
<? $item = v::get($arResult, ['ITEMS', 0]) ?>
<? if (!v::isEmpty($item)): ?>
    <div class="video-item">
        <? $url = Videos::embedUrl(Videos::youtubeId($item['PROPERTIES']['URL']['VALUE'])) ?>
        <iframe src="<?= $url ?>" frameborder="0" allowfullscreen></iframe>
        <p class="name"><?= $item['NAME'] ?></p>
        <a class="btn" href="<?= v::path('videos') ?>">Смотреть все видео</a>
    </div>
<? endif ?>