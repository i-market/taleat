<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\Videos;
use App\View as v;
?>
<? if (!v::isEmpty($arResult['ITEMS'])): ?>
    <section class="videos section">
        <div class="section-title">
            <div class="wrap">
                <div class="section-title-block">
                    <h2>Полезное видео</h2>
                    <div class="section-title-link">|<a href="<?= v::path('videos') ?>">все <span class="hidden">видеоуроки</span></a></div>
                </div>
            </div>
        </div>
        <div class="wrap">
            <div class="wrap-min">
                <div class="grid">
                    <? foreach ($arResult['ITEMS'] as $item): ?>
                        <? $url = Videos::embedUrl(Videos::youtubeId($item['PROPERTIES']['URL']['VALUE'])) ?>
                        <div class="col col-3 video-item">
                            <iframe src="<?= $url ?>" frameborder="0" allowfullscreen></iframe>
                            <p class="name"><?= $item['NAME'] ?></p>
                        </div>
                    <? endforeach ?>
                </div>
            </div>
        </div>
    </section>
<? endif ?>
