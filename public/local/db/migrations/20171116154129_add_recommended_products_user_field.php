<?php

use Bitrix\Main\Application;
use Phinx\Migration\AbstractMigration;

class AddRecommendedProductsUserField extends AbstractMigration {
    public $fields = [
        'ENTITY_ID' => 'IBLOCK_3_SECTION', // products
        'FIELD_NAME' => 'UF_RECOMMENDED',
        'USER_TYPE_ID' => 'iblock_element',
        'XML_ID' => '',
        'SORT' => '100',
        'MULTIPLE' => 'Y',
        'MANDATORY' => NULL,
        'SHOW_FILTER' => 'N',
        'SHOW_IN_LIST' => NULL,
        'EDIT_IN_LIST' => NULL,
        'IS_SEARCHABLE' => NULL,
        'SETTINGS' =>
            [
                'IBLOCK_TYPE_ID' => 'catalog',
                'IBLOCK_ID' => '3',
                'DEFAULT_VALUE' => '',
                'DISPLAY' => 'CHECKBOX',
                'LIST_HEIGHT' => '5',
                'ACTIVE_FILTER' => 'Y',
            ],
        'EDIT_FORM_LABEL' =>
            [
                'ru' => 'Рекомендуемые товары',
                'en' => 'Recommendations',
            ],
        'LIST_COLUMN_LABEL' =>
            [
                'ru' => 'Рекомендуемые товары',
                'en' => 'Recommendations',
            ],
        'LIST_FILTER_LABEL' =>
            [
                'ru' => 'Рекомендуемые товары',
                'en' => 'Recommendations',
            ],
        'ERROR_MESSAGE' =>
            [
                'ru' => '',
                'en' => '',
            ],
        'HELP_MESSAGE' =>
            [
                'ru' => '',
                'en' => '',
            ],
    ];

    function up() {
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            $uf = new CUserTypeEntity;
            $result = $uf->Add($this->fields);
            if (!$result) {
                throw new Exception("failed");
            }
            $conn->commitTransaction();
        } catch (Exception $e) {
            $conn->rollbackTransaction();
            throw $e;
        }
    }

    function down() {
        $by = null;
        $order = null;
        $rsData = CUserTypeEntity::GetList(
            array($by => $order),
            array('FIELD_NAME' => $this->fields['FIELD_NAME'])
        );
        if ($arRes = $rsData->Fetch()) {
            $obCUserTypeEntity = new CUserTypeEntity;
            $obCUserTypeEntity->Delete($arRes['ID']);
        }
    }
}
