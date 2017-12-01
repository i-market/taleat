<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Мы предлагаем широкий ассортимент аксессуаров  к бытовой технике  фирмы Braun (Браун)");
$APPLICATION->SetPageProperty("keywords", "Сетки к бритвам, зубные насадки к электрическим зубным щеткам, запасные части к Браун, Braun, аксессуары к бытовой технике");
$APPLICATION->SetTitle("Отзывы");
$APPLICATION->SetPageProperty("layout", "bare");

use App\View\FormMacros as m;
use App\View as v;
use App\Review;
use App\App;
use Bitrix\Main\Application;
use Bitrix\Main\Web\Uri;
use Core\Util;

$withParams = function ($params) {
    $uri = Application::getInstance()->getContext()->getRequest()->getRequestUri();
    return (new Uri($uri))->addParams($params)->getUri();
};

$result = [];
// TODO refactor
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = Review::create($_REQUEST);
    if ($result['success']) {
        LocalRedirect($withParams(['success' => '1']), false, '303 See Other');
    }
} elseif (v::get($_REQUEST, 'success')) {
    $result = Review::successResult();
}
?>

<section class="reviews">
    <div class="section-title">
        <div class="wrap">
            <div class="section-title-block">
                <h2><? $APPLICATION->ShowTitle(false) ?></h2>
            </div>
        </div>
    </div>
    <div class="wrap">
        <div class="grid">
            <div class="col col-2">
                <?$APPLICATION->IncludeComponent("bitrix:news.list", "reviews", array(
                    "IBLOCK_TYPE" => "otzivi",
                    "IBLOCK_ID" => "5",
                    "NEWS_COUNT" => 10,
                    "SORT_BY1" => "ACTIVE_FROM",
                    "SORT_ORDER1" => "DESC",
                    "SORT_BY2" => "SORT",
                    "SORT_ORDER2" => "ASC",
                    "FILTER_NAME" => "",
                    "FIELD_CODE" => array(
                        0 => "DETAIL_PICTURE",
                        1 => "",
                    ),
                    "PROPERTY_CODE" => array(
                        0 => "",
                        1 => "LINK",
                        2 => "space",
                        3 => "ORDER_NUM",
                        4 => "CITY",
                    ),
                    "CHECK_DATES" => "N",
                    "DETAIL_URL" => "",
                    "AJAX_MODE" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "N",
                    "AJAX_OPTION_HISTORY" => "N",
                    "CACHE_TYPE" => "N",
                    "CACHE_TIME" => "3600",
                    "CACHE_FILTER" => "N",
                    "CACHE_GROUPS" => "Y",
                    "PREVIEW_TRUNCATE_LEN" => "",
                    "ACTIVE_DATE_FORMAT" => "d.m.Y",
                    "SET_TITLE" => "N",
                    "SET_STATUS_404" => "N",
                    "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                    "ADD_SECTIONS_CHAIN" => "N",
                    "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                    "PARENT_SECTION" => "",
                    "PARENT_SECTION_CODE" => "",
                    "DISPLAY_TOP_PAGER" => "N",
                    "DISPLAY_BOTTOM_PAGER" => "Y",
                    "PAGER_TITLE" => "",
                    "PAGER_SHOW_ALWAYS" => "N",
                    "PAGER_TEMPLATE" => "",
                    "PAGER_DESC_NUMBERING" => "N",
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                    "PAGER_SHOW_ALL" => "N",
                    "DISPLAY_DATE" => "Y",
                    "DISPLAY_NAME" => "Y",
                    "DISPLAY_PICTURE" => "Y",
                    "DISPLAY_PREVIEW_TEXT" => "Y",
                    "AJAX_OPTION_ADDITIONAL" => ""
                ),
                    false
                );?>
            </div>
            <div class="col col-2">
                <form class="validate form Leave-feedback" method="post" action="">
                    <? if (!v::isEmpty($result)): ?>
                        <? // TODO error handling ?>
                        <? App::getInstance()->assert($result['success']) ?>
                        <div class="form__message form__message--success">
                            <?= $result['message']['text'] ?>
                        </div>
                    <? else: ?>
                        <p class="title"></p>
                        <? m::showInput('NAME', 'Имя *', ['required' => true]) ?>
                        <? m::showInput('ORDER_NUM', 'Номер заказа *', ['required' => true]) ?>
                        <? m::showInput('CITY', 'Город *', ['required' => true]) ?>
                        <!--<textarea placeholder="Ваш отзыв" class="resize"></textarea>-->
                        <label class="label_textarea">
                            <textarea name="TEXT" required placeholder="Ваш отзыв"><?= v::escAttr(v::get($_REQUEST, 'TEXT')) ?></textarea>
                        </label>
                        <div class="wrap-checkbox">
                            <? $id = 'input-'.Util::uniqueId() ?>
                            <input class="checkbox"
                                   type="checkbox"
                                   name="legal"
                                   value="1"
                                   required
                                   data-msg-required="Пожалуйста, дайте согласие на обработку персональных данных."
                                   checked
                                   hidden="hidden"
                                   id="<?= $id ?>">
                            <label for="<?= $id ?>">Я согласен на обработку <a href="#">персональных данных</a></label>
                        </div>
                        <button type="submit" class="download-btn">Отправить отзыв</button>
                    <? endif ?>
                    <p class="reviews-allert">Если у Вас остались вопросы по поводу ремонта вашей техники, Вы можете написать их директору компании</p>
                    <span class="download-btn" data-modal="contact-modal">Написать письмо директору</span>
                </form>
            </div>
        </div>
    </div>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>