<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Сетки к бритвам, зубные насадки к электрическим зубным щеткам, запасные части к Браун, Braun, аксессуары к бытовой технике");
$APPLICATION->SetPageProperty("description", "Мы предлагаем широкий ассортимент аксессуаров  к бытовой технике  фирмы Braun (Браун)");
$APPLICATION->SetTitle("Главная | TALEAT");

use App\View as v;
?>

<?
global $arFiltNew;

$arFiltNew=array("PROPERTY_PROP_VALUE"=>"Новинки");
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
            <div class="slide"><a href="#"><img src="<?= v::asset('images/pic/labels/1.png') ?>" alt=""></a></div>
            <div class="slide"><a href="#"><img src="<?= v::asset('images/pic/labels/2.png') ?>" alt=""></a></div>
            <div class="slide"><a href="#"><img src="<?= v::asset('images/pic/labels/3.png') ?>" alt=""></a></div>
            <div class="slide"><a href="#"><img src="<?= v::asset('images/pic/labels/4.png') ?>" alt=""></a></div>
            <div class="slide"><a href="#"><img src="<?= v::asset('images/pic/labels/5.png') ?>" alt=""></a></div>
            <div class="slide"><a href="#"><img src="<?= v::asset('images/pic/labels/2.png') ?>" alt=""></a></div>
        </div>
    </div>
</section>
<section class="wrap-items-slider wrap-slider section">
    <div class="section-title">
        <div class="wrap">
            <div class="section-title-block">
                <h2>Актуальные товары</h2>
                <div class="section-title-link">|<a href="#">все <span class="hidden">товары</span></a></div>
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
        <div class="items-slider slider">
            <a href="#" class="item-box">
                <div class="img">
                    <img src="<?= v::asset('images/pic/items/1.png') ?>" alt="">
                </div>
                <div class="info">
                    <p class="label-unfo">
                        <span class="articul">SX1045</span>
                        <span class="label-name">Braun</span>
                    </p>
                    <p class="name">Колба для кофеварки Delonghi</p>
                    <div class="price-info">
                        <span class="price"><span>1680</span> руб.</span>
                        <span class="cart"></span>
                    </div>
                </div>
            </a>
            <a href="#" class="item-box">
                <div class="img">
                    <img src="<?= v::asset('images/pic/items/2.png') ?>" alt="">
                </div>
                <div class="info">
                    <p class="label-unfo">
                        <span class="articul">SX1045</span>
                        <span class="label-name">Braun</span>
                    </p>
                    <p class="name">Колба для кофеварки Delonghi</p>
                    <div class="price-info">
                        <span class="price"><span>1680</span> руб.</span>
                        <span class="cart"></span>
                    </div>
                </div>
            </a>
            <a href="#" class="item-box">
                <div class="img">
                    <img src="<?= v::asset('images/pic/items/3.png') ?>" alt="">
                </div>
                <div class="info">
                    <p class="label-unfo">
                        <span class="articul">SX1045</span>
                        <span class="label-name">Braun</span>
                    </p>
                    <p class="name">Колба для кофеварки Delonghi</p>
                    <div class="price-info">
                        <span class="price"><span>1680</span> руб.</span>
                        <span class="cart"></span>
                    </div>
                </div>
            </a>
            <a href="#" class="item-box">
                <div class="img">
                    <img src="<?= v::asset('images/pic/items/4.png') ?>" alt="">
                </div>
                <div class="info">
                    <p class="label-unfo">
                        <span class="articul">SX1045</span>
                        <span class="label-name">Braun</span>
                    </p>
                    <p class="name">Колба для кофеварки Delonghi</p>
                    <div class="price-info">
                        <span class="price"><span>1680</span> руб.</span>
                        <span class="cart"></span>
                    </div>
                </div>
            </a>
            <a href="#" class="item-box">
                <div class="img">
                    <img src="<?= v::asset('images/pic/items/3.png') ?>" alt="">
                </div>
                <div class="info">
                    <p class="label-unfo">
                        <span class="articul">SX1045</span>
                        <span class="label-name">Braun</span>
                    </p>
                    <p class="name">Колба для кофеварки Delonghi</p>
                    <div class="price-info">
                        <span class="price"><span>1680</span> руб.</span>
                        <span class="cart"></span>
                    </div>
                </div>
            </a>
        </div>
    </div>
</section>
<section class="wrap-shops-slider wrap-slider section hidden">
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
        <div class="shops-slider slider">
            <div class="slide"><a href="#"><img src="<?= v::asset('images/pic/labels/6.png') ?>" alt=""></a></div>
            <div class="slide"><a href="#"><img src="<?= v::asset('images/pic/labels/7.png') ?>" alt=""></a></div>
            <div class="slide"><a href="#"><img src="<?= v::asset('images/pic/labels/8.png') ?>" alt=""></a></div>
            <div class="slide"><a href="#"><img src="<?= v::asset('images/pic/labels/9.png') ?>" alt=""></a></div>
            <div class="slide"><a href="#"><img src="<?= v::asset('images/pic/labels/7.png') ?>" alt=""></a></div>
        </div>
    </div>
</section>
<section class="wrap-sertificate-slider wrap-slider section">
    <div class="section-title">
        <div class="wrap">
            <div class="section-title-block">
                <h2>Наши сертификаты</h2>
                <div class="section-title-link">|<a href="#">все <span class="hidden">сертификаты</span></a></div>
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
            <a href="<?= v::asset('images/pic/sert/1.jpg') ?>" class="sertificate-item" data-fancybox="images">
                <div class="img">
                    <img src="<?= v::asset('images/pic/sert/1.jpg') ?>" alt="">
                </div>
                <p class="name">“KENWOOD”</p>
            </a>
            <a href="<?= v::asset('images/pic/sert/2.jpg') ?>" class="sertificate-item" data-fancybox="images">
                <div class="img">
                    <img src="<?= v::asset('images/pic/sert/2.jpg') ?>" alt="">
                </div>
                <p class="name">“PHILIPS”</p>
            </a>
            <a href="<?= v::asset('images/pic/sert/1.jpg') ?>" class="sertificate-item" data-fancybox="images">
                <div class="img">
                    <img src="<?= v::asset('images/pic/sert/1.jpg') ?>" alt="">
                </div>
                <p class="name">“KENWOOD”</p>
            </a>
            <a href="<?= v::asset('images/pic/sert/2.jpg') ?>" class="sertificate-item" data-fancybox="images">
                <div class="img">
                    <img src="<?= v::asset('images/pic/sert/2.jpg') ?>" alt="">
                </div>
                <p class="name">“PHILIPS”</p>
            </a>
            <a href="<?= v::asset('images/pic/sert/1.jpg') ?>" class="sertificate-item" data-fancybox="images">
                <div class="img">
                    <img src="<?= v::asset('images/pic/sert/1.jpg') ?>" alt="">
                </div>
                <p class="name">“KENWOOD”</p>
            </a>
            <a href="<?= v::asset('images/pic/sert/2.jpg') ?>" class="sertificate-item" data-fancybox="images">
                <div class="img">
                    <img src="<?= v::asset('images/pic/sert/2.jpg') ?>" alt="">
                </div>
                <p class="name">“PHILIPS”</p>
            </a>
        </div>
    </div>
</section>
<section class="news section">
    <div class="section-title">
        <div class="wrap">
            <div class="section-title-block">
                <h2>Новости</h2>
                <div class="section-title-link">|<a href="#">все <span class="hidden">новости</span></a></div>
            </div>
        </div>
    </div>
    <div class="wrap">
        <div class="grid">
            <div class="col col-2">
                <a href="#" class="news-item">
                    <div class="img">
                        <img src="<?= v::asset('images/pic/news/1.jpg') ?>" alt="">
                    </div>
                    <div class="info">
                        <p class="date">12.07.2017</p>
                        <p class="title">Безопасность при ремонте бытовых приборов и техники</p>
                        <p class="text">Стиральные машины, пылесосы, обогреватели, кондиционеры, холодильники – далеко не полный перечень устройств, без которых жизнь современного горожанина превратилась бы в настоящий ад.</p>
                    </div>
                </a>
            </div>
            <div class="col col-2">
                <a href="#" class="news-item">
                    <div class="img">
                        <img src="<?= v::asset('images/pic/news/1.jpg') ?>" alt="">
                    </div>
                    <div class="info">
                        <p class="date">12.07.2017</p>
                        <p class="title">Безопасность при ремонте бытовых приборов и техники</p>
                        <p class="text">Стиральные машины, пылесосы, обогреватели, кондиционеры, холодильники – далеко не полный перечень устройств, без которых жизнь современного горожанина превратилась бы в настоящий ад.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>
<section class="useful section hidden">
    <div class="section-title">
        <div class="wrap">
            <div class="section-title-block">
                <h2>Поленое</h2>
                <div class="section-title-link">|<a href="#">все статьи</a></div>
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
<section class="videos section">
    <div class="section-title">
        <div class="wrap">
            <div class="section-title-block">
                <h2>Полезное видео</h2>
                <div class="section-title-link">|<a href="#">все <span class="hidden">видеуроки</span></a></div>
            </div>
        </div>
    </div>
    <div class="wrap">
        <div class="wrap-min">
            <div class="grid">
                <div class="col col-3 video-item">
                    <iframe src="https://www.youtube.com/embed/hj7ZYjVFDGI?rel=0" frameborder="0" allowfullscreen></iframe>
                    <p class="name">Ремонт бытовой техники на дому в Москве</p>
                </div>
                <div class="col col-3 video-item">
                    <iframe src="https://www.youtube.com/embed/hj7ZYjVFDGI?rel=0" frameborder="0" allowfullscreen></iframe>
                    <p class="name">Далеко не полный перечень устройств, без которых жизнь современного</p>
                </div>
                <div class="col col-3 video-item">
                    <iframe src="https://www.youtube.com/embed/hj7ZYjVFDGI?rel=0" frameborder="0" allowfullscreen></iframe>
                    <p class="name">Ремонт бытовой техники на дому в Москве</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="about">
    <div class="section-title">
        <div class="wrap">
            <div class="section-title-block">
               <div class="editable-area">
                   <? $APPLICATION->IncludeComponent(
                       "bitrix:main.include",
                       "",
                       Array(
                           "AREA_FILE_SHOW" => "file",
                           "PATH" => v::includedArea('homepage/about_heading.php')
                       )
                   ); ?>
               </div>
            </div>
        </div>
    </div>
    <div class="wrap">
        <div class="wrap-min">
            <div class="editable-area">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => v::includedArea('homepage/about_body.php')
                    )
                ); ?>
            </div>
            <a href="#" class="read-more"><span>читать дальше</span></a>
        </div>
    </div>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
