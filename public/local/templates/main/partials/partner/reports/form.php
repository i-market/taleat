<?
use App\View as v;
use Core\Util;
?>

<? $placeholderOpt = '<option value="" hidden>Выбрать...</option>' ?>

<? // TODO inputs masks ?>
<? // TODO validate required checkboxes, radios ?>
<? // TODO hidden inputs ?>
<? // TODO date placeholders ?>
<form action="" method="post" id="" class="validate technical-conclusion-form">
    <? $sc = v::get($fields, 'SC') ?>
    <div class="item">
        <div class="left">Выдано сервисным центром:</div>
        <div class="right">
            <input name="SC[NAME]" value="<?= v::get($sc, 'NAME') ?>" required placeholder="Название СЦ" type="text" class="input">
        </div>
    </div>
    <div class="item">
        <div class="left">Дата выдачи заключения:</div>
        <div class="right">
            <input name="SC[DATA_ZAKL]" value="<?= v::get($sc, 'DATA_ZAKL') ?>" required type="text" class="input">
        </div>
    </div>
    <div class="item">
        <div class="left">Адрес сервисного центра:</div>
        <div class="right">
            <div class="label_textarea">
                <textarea name="SC[ADRES]" required><?= v::get($sc, 'ADRES') ?></textarea>
            </div>
        </div>
    </div>
    <div class="item">
        <div class="left">Телефон СЦ:</div>
        <div class="right">
            <? // TODO should auto prepend +7 when it's missing ?>
            <input name="SC[PHONE]" value="<?= v::get($sc, 'PHONE') ?>" required type="tel" class="input">
        </div>
    </div>

    <h3>I. Данные о владельце изделия</h3>
    <? $owner = v::get($fields, 'VLADELEC') ?>
    <div class="item">
        <div class="left">ФИО:</div>
        <div class="right">
            <input name="VLADELEC[FIO]" value="<?= v::get($owner, 'FIO') ?>" required type="text" class="input">
        </div>
    </div>
    <div class="item">
        <div class="left">Контактный телефон:</div>
        <div class="right">
            <input name="VLADELEC[PHONE]" value="<?= v::get($owner, 'PHONE') ?>" required type="tel" class="input">
        </div>
    </div>
    <div class="item">
        <div class="left">Адрес:</div>
        <div class="right">
            <div class="label_textarea">
                <textarea name="VLADELEC[ADRES]" required><?= v::get($owner, 'ADRES') ?></textarea>
            </div>
        </div>
    </div>

    <h3>II. Данные об изделии</h3>
    <? $product = v::get($fields, 'IZDEL') ?>
    <? $productId = v::get($product, 'NAME') ?>
    <div class="item">
        <div class="left">Наименование:</div>
        <div class="right">
            <select name="IZDEL[NAME]" required>
                <?= $placeholderOpt ?>
                <? foreach ($products as $p): ?>
                    <? $selected = $p['ID'] == $productId ?>
                    <option value="<?= $p['ID'] ?>" <?= $selected ? 'selected' : '' ?>><?= $p['NAME'] ?></option>
                <? endforeach ?>
            </select>
        </div>
    </div>
    <div class="item">
        <div class="left">Модель:</div>
        <div class="right">
            <select name="IZDEL[MODEL]" required <?= v::isEmpty($models) ? 'disabled' : '' ?>>
                <?= $placeholderOpt ?>
                <? foreach ($models as $m): ?>
                    <? $selected = $m['VALUE'] == v::get($product, 'MODEL') ?>
                    <option value="<?= $m['VALUE'] ?>" <?= $selected ? 'selected' : '' ?>><?= $m['VALUE_ENUM'] ?></option>
                <? endforeach ?>
            </select>
        </div>
    </div>
    <div class="item">
        <div class="left">Тип:</div>
        <div class="right">
            <input name="IZDEL[TYPE]" value="<?= v::get($product, 'TYPE') ?>" required type="text" class="input">
        </div>
    </div>
    <div class="item">
        <div class="left">Дата производства:</div>
        <div class="right">
            <input name="IZDEL[DATA_PROIZV]" value="<?= v::get($product, 'DATA_PROIZV') ?>" required type="text" class="input">
        </div>
    </div>
    <div class="item">
        <div class="left">Дата продажи:</div>
        <div class="right">
            <input name="IZDEL[DATA_PRODAJI]" value="<?= v::get($product, 'DATA_PRODAJI') ?>" type="text" class="input">
        </div>
    </div>
    <div class="item item--col">
        <? // TODO validate: require ?>
        <div class="left">Комплектность:</div>
        <div class="right">
            <div class="grid">
                <? foreach ($completeness as $comp): ?>
                    <? $checked = in_array($comp['ID'], v::get($product, 'KOMPLEKT', [])) ?>
                    <? $id = 'comp-'.Util::uniqueId(); ?>
                    <div class="col col-2">
                        <div class="wrap-checkbox">
                            <input type="checkbox"
                                   name="IZDEL[KOMPLEKT][]"
                                   value="<?= $comp['ID'] ?>"
                                   <?= $checked ? 'checked' : '' ?>
                                   id="<?= $id ?>"
                                   hidden="hidden">
                            <label for="<?= $id ?>"><?= $comp['VALUE'] ?></label>
                        </div>
                    </div>
                <? endforeach ?>
            </div>
        </div>
    </div>
    <div class="item">
        <div class="left">Дата поступления в сервисный центр:</div>
        <div class="right">
            <input name="IZDEL[DATA_POSTUP]" value="<?= v::get($product, 'DATA_POSTUP') ?>" required type="text" class="input">
        </div>
    </div>
    <div class="item">
        <div class="left">Внешние признаки неисправности:</div>
        <div class="right">
            <div class="label_textarea">
                <textarea name="IZDEL[PRIZNAKI_NEISPR]" required><?= v::get($product, 'PRIZNAKI_NEISPR') ?></textarea>
            </div>
        </div>
    </div>
    <div class="item">
        <div class="left">Сведения о предыдущих ремонтах:</div>
        <div class="right">
            <div class="label_textarea">
                <? $ph = '№ гарантийной квитанции, даты приема и выдачи изделия' ?>
                <textarea name="IZDEL[SVEDINIYA]" placeholder="<?= $ph ?>"><?= v::get($product, 'SVEDINIYA') ?></textarea>
            </div>
        </div>
    </div>

    <h3>III. Данные освидетельствования</h3>
    <? $data = v::get($fields, 'DAN') ?>
    <div class="item">
        <div class="left">Выявленный дефект:</div>
        <div class="right">
            <div class="label_textarea">
                <textarea name="DAN[VIEV_DEFEKT]" required><?= v::get($data, 'VIEV_DEFEKT') ?></textarea>
            </div>
        </div>
    </div>
    <div class="item item--align-start">
        <div class="left">Заключение о причинах несправности:</div>
        <div class="right">
            <? foreach ($defects as $id => $name): ?>
                <? $checked = v::get($data, 'DEFEKT') == $id ?>
                <? $elemId = 'defect-'.Util::uniqueId(); ?>
                <div class="wrap-radio">
                    <input type="radio" name="DAN[DEFEKT]" value="<?= $id ?>" required id="<?= $elemId ?>" hidden="hidden" <?= $checked ? 'checked' : '' ?>>
                    <label for="<?= $elemId ?>"><?= $name ?></label>
                </div>
            <? endforeach ?>
            <div class="label_textarea">
                <textarea name="DAN[DEFEKT3_DESCR]" <?= !$hasDefectDescription ? 'disabled' : '' ?>><?= v::get($data, 'DEFEKT3_DESCR') ?></textarea>
            </div>
        </div>
    </div>

    <? // TODO waiting for requirements ?>
    <div class="TODO-mockup" style="display: none">
        <p class="text">Запчасти, необходимые для восстановления:</p>
        <? // TODO see legacy, unused? research $zap business logic ?>
        <div class="parts-in-stock">
            <div class="parts-in-stock-item hidden">
                <div class="wrap-stock-input">
                    <div class="wrap-stock-item">
                        <p class="text center">Название запчасти</p>
                    </div>
                    <div class="wrap-stock-item">
                        <p class="text center">Артикул</p>
                    </div>
                </div>
                <div class="stock">
                    <p class="text center">Наличие на складе</p>
                </div>
            </div>
            <div class="parts-in-stock-item">
                <div class="wrap-stock-input">
                    <div class="wrap-stock-item">
                        <input type="text" disabled placeholder="Не заполнять!" class="input">
                    </div>
                    <div class="wrap-stock-item">
                        <input type="text" disabled placeholder="Не заполнять!" class="input">
                    </div>
                </div>
                <div class="stock">
                    <div class="wrap-radio">
                        <input type="radio" name="family-2" id="1123" hidden="hidden">
                        <label for="1123">Да</label>
                    </div>
                    <div class="wrap-radio">
                        <input type="radio" name="family-2" id="12234" hidden="hidden" checked>
                        <label for="12234">Нет</label>
                    </div>
                </div>
            </div>
            <div class="parts-in-stock-item">
                <div class="wrap-stock-input">
                    <div class="wrap-stock-item">
                        <input type="text" disabled placeholder="Не заполнять!" class="input">
                    </div>
                    <div class="wrap-stock-item">
                        <input type="text" disabled placeholder="Не заполнять!" class="input">
                    </div>
                </div>
                <div class="stock">
                    <div class="wrap-radio">
                        <input type="radio" name="family-4" id="15223" hidden="hidden">
                        <label for="15223">Да</label>
                    </div>
                    <div class="wrap-radio">
                        <input type="radio" name="family-4" id="234234" hidden="hidden" checked>
                        <label for="234234">Нет</label>
                    </div>
                </div>
            </div>
            <div class="parts-in-stock-item">
                <div class="wrap-stock-input">
                    <div class="wrap-stock-item">
                        <input type="text" disabled placeholder="Не заполнять!" class="input">
                    </div>
                    <div class="wrap-stock-item">
                        <input type="text" disabled placeholder="Не заполнять!" class="input">
                    </div>
                </div>
                <div class="stock">
                    <div class="wrap-radio">
                        <input type="radio" name="family-5" id="67" hidden="hidden">
                        <label for="67">Да</label>
                    </div>
                    <div class="wrap-radio">
                        <input type="radio" name="family-5" id="68" hidden="hidden" checked>
                        <label for="68">Нет</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h3>IV. Причина невозможности ремонта</h3>
    <? foreach ($reasons as $id => $name): ?>
        <? $checked = v::get($fields, 'PRICHINA') == $id ?>
        <? $elemId = 'reason-'.Util::uniqueId(); ?>
        <div class="wrap-radio">
            <input type="radio" name="PRICHINA" value="<?= $id ?>" id="<?= $elemId ?>" hidden="hidden" <?= $checked ? 'checked' : '' ?>>
            <label for="<?= $elemId ?>"><?= $name ?></label>
        </div>
    <? endforeach ?>

    <h3>V. Местонахождение изделия после технического освидетельствования</h3>
    <? foreach ($places as $id => $name): ?>
        <? $checked = v::get($fields, 'ITEM_PLACE') == $id ?>
        <? $elemId = 'place-'.Util::uniqueId(); ?>
        <div class="wrap-radio">
            <input type="radio" name="ITEM_PLACE" value="<?= $id ?>" required id="<?= $elemId ?>" hidden="hidden" <?= $checked ? 'checked' : '' ?>>
            <label for="<?= $elemId ?>"><?= $name ?></label>
        </div>
    <? endforeach ?>

    <? // TODO validate: require file ?>
    <h3>Прикрепить скан гарантийного талона или чека</h3>
    <div class="wrap-file"><input type="file"></div>
    <div class="wrap-file"><input type="file"></div>
    <div class="wrap-file"><input type="file"></div>
    <div class="bottom">
        <p class="text"><b>Все поля обязательны для заполнения</b></p>
        <button type="submit" class="btn">Отправить заключение</button>
    </div>
</form>
