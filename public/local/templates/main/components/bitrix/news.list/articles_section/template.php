<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<? if (!v::isEmpty($arResult['ITEMS'])): ?>
    <section class="useful section hidden">
        <div class="section-title">
            <div class="wrap">
                <div class="section-title-block">
                    <h2>Полезное</h2>
                    <div class="section-title-link">|<a href="<?= v::path('articles') ?>">все статьи</a></div>
                </div>
            </div>
        </div>
        <div class="wrap useful-block">
            <div class="left">
                <div class="wrap-useful-slider">
                    <div class="useful-slider">
                        <div class="slide">
                            <div class="img">
                                <img src="<?= v::asset('images/pic/pic-4.jpg') ?>" alt="">
                            </div>
                            <div class="info">
                                <p class="title">Безопасность при ремонте бытовых приборов и техники</p>
                                <p class="paragraph">Стиральные машины, пылесосы, обогреватели, кондиционеры, холодильники – далеко не полный перечень устройств, без которых жизнь современного горожанина превратилась бы в настоящий ад. Люди просто отвыкли обходиться без всего этого. Но бытовая техника периодически выходит из строя, и встает вопрос, что с ней делать.  Всем ли доступен ремонт бытовой техники? Давайте об этом поговорим.</p>
                                <p class="more"><a href="#">Подробнее...</a></p>
                            </div>
                        </div>
                        <div class="slide">
                            <div class="img">
                                <img src="<?= v::asset('images/pic/pic-4.jpg') ?>" alt="">
                            </div>
                            <div class="info">
                                <p class="title">Безопасность при ремонте бытовых приборов и техники</p>
                                <p class="paragraph">Стиральные машины, пылесосы, обогреватели, кондиционеры, холодильники – далеко не полный перечень устройств, без которых жизнь современного горожанина превратилась бы в настоящий ад. Люди просто отвыкли обходиться без всего этого. Но бытовая техника периодически выходит из строя, и встает вопрос, что с ней делать.  Всем ли доступен ремонт бытовой техники? Давайте об этом поговорим.</p>
                                <p class="more"><a href="#">Подробнее...</a></p>
                            </div>
                        </div>
                        <div class="slide">
                            <div class="img">
                                <img src="<?= v::asset('images/pic/pic-4.jpg') ?>" alt="">
                            </div>
                            <div class="info">
                                <p class="title">Безопасность при ремонте бытовых приборов и техники</p>
                                <p class="paragraph">Стиральные машины, пылесосы, обогреватели, кондиционеры, холодильники – далеко не полный перечень устройств, без которых жизнь современного горожанина превратилась бы в настоящий ад. Люди просто отвыкли обходиться без всего этого. Но бытовая техника периодически выходит из строя, и встает вопрос, что с ней делать.  Всем ли доступен ремонт бытовой техники? Давайте об этом поговорим.</p>
                                <p class="more"><a href="#">Подробнее...</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="dots"></div>
                </div>
            </div>
            <div class="right">
                <div class="useful-item">
                    <p class="title">Бренды которые мы ремонтируем</p>
                    <p class="paragraph">Стиральные машины, пылесосы, обогреватели, кондиционеры, холодильники – далеко не полный...</p>
                    <p class="more"><a href="#">Подробнее...</a></p>
                </div>
                <div class="useful-item">
                    <p class="title">Бренды которые мы ремонтируем</p>
                    <p class="paragraph">Стиральные машины, пылесосы, обогреватели, кондиционеры, холодильники – далеко не полный...</p>
                    <p class="more"><a href="#">Подробнее...</a></p>
                </div>
                <div class="useful-item">
                    <p class="title">Бренды которые мы ремонтируем</p>
                    <p class="paragraph">Стиральные машины, пылесосы, обогреватели, кондиционеры, холодильники – далеко не полный...</p>
                    <p class="more"><a href="#">Подробнее...</a></p>
                </div>
                <div class="useful-item">
                    <p class="title">Бренды которые мы ремонтируем</p>
                    <p class="paragraph">Стиральные машины, пылесосы, обогреватели, кондиционеры, холодильники – далеко не полный...</p>
                    <p class="more"><a href="#">Подробнее...</a></p>
                </div>
            </div>
        </div>
    </section>
<? endif ?>