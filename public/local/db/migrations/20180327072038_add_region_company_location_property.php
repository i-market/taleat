<?php

use Bitrix\Main\Application;
use Phinx\Migration\AbstractMigration;
use Core\Underscore as _;

class AddRegionCompanyLocationProperty extends AbstractMigration {
    function up() {
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            $iblockId = 7;
            $prop = new CIBlockProperty();
            $result = $prop->Add([
                'NAME' => 'Местоположение',
                'ACTIVE' => 'Y',
                'IS_REQUIRED' => 'N',
                'SORT' => '500',
                'CODE' => 'LOCATION',
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE' => 'map_yandex',
                'FILTRABLE' => 'N',
                'IBLOCK_ID' => $iblockId,
            ]);
            if (!$result) {
                throw new \Exception($prop->LAST_ERROR);
            }

            $_addresses = json_decode(file_get_contents(__DIR__.'/../resources/addresses.json'), true);
            $_companies = json_decode(file_get_contents(__DIR__.'/../resources/region_companies.json'), true);
            $metadata = function ($obj) {
                return _::get($obj, 'GeoObject.metaDataProperty.GeocoderMetaData');
            };
            $lngLat = function ($obj) {
                return _::get($obj, 'GeoObject.Point.pos');
            };
            $addressKey = function ($address) {
                return mb_ereg_replace('\s|&nbsp;', '', $address);
            };
            $addresses = _::keyBy(_::compose($addressKey, [_::class, 'first']),
                _::map($_addresses, function ($item) use ($metadata, $lngLat) {
                    if (in_array('malformed', _::get($item, 'tags', []))) {
                        return [$item['address'], null, null];
                    }
                    $objs = _::get($item, 'geocodingResult.response.GeoObjectCollection.featureMember');
                    $houseObjs = _::filter($objs, function ($obj) use ($metadata) {
                        return $metadata($obj)['kind'] === 'house';
                    });
                    $obj = _::first($houseObjs); // best guess
                    if ($obj) {
                        return [$item['address'], $metadata($obj)['text'], $lngLat($obj)];
                    } else {
                        if (!isset($item['lngLat'])) {
                            throw new \Exception(var_export($item, true));
                        }
                        return [$item['address'], null, $item['lngLat']];
                    }
                })
            );
            $companies = _::map($_companies, function ($company) use ($addresses, $addressKey) {
                $subj = $addressKey($company['text']); // TODO what if `text` is stale?
                $address = _::find($addresses, function ($_, $k) use ($subj, $addressKey) {
                    return mb_strpos($subj, $k) !== false;
                });
                if (!$address) {
                    throw new \Exception(var_export($company, true));
                }
                return array_merge($company, $address ? array_combine(['address', 'addressNormalized', 'lngLat'], $address) : []);
            });

            foreach ($companies as $company) {
                if ($company['lngLat'] === null) { // *cough*
                    continue;
                }
                list($lng, $lat) = explode(' ', $company['lngLat']);
                if (!$lng || !$lat) {
                    throw new \Exception(var_export($company, true));
                }
                CIBlockElement::SetPropertyValuesEx($company['id'], $iblockId, ['LOCATION' => $lat.','.$lng]);
            }

            $conn->commitTransaction();
        } catch (Exception $e) {
            $conn->rollbackTransaction();
            throw $e;
        }
    }

    function down() {}
}
