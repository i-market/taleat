<?php

namespace App;

use Bex\Tools\Iblock\IblockTools;
use CGroup;
use CIBlockSection;
use CUser;
use iter;

class Partner {
    static function isGroupFilterEnabled(CUser $user) {
        $hasFullAccess = in_array(CGroup::GetIDByCode(UserGroup::FULL_BRAND_ACCESS), CUser::GetUserGroup($user->GetID()));
        return !$hasFullAccess;
    }

    static function documentSections(CUser $user) {
        $filter = [
            'IBLOCK_ID' => IblockTools::find(Iblock::PARTNER_TYPE, Iblock::DOCUMENTS)->id(),
            'ACTIVE' => 'Y'
        ];
        if (self::isGroupFilterEnabled($user)) {
            $filter[] = [
                'LOGIC' => 'OR',
                ['UF_USER_GROUP' => false],
                ['UF_USER_GROUP' => CUser::GetUserGroup($user->GetID())]
            ];
        }
        return iter\toArray(Iblock::iter(CIBlockSection::GetList([], $filter)));
    }

    static function feedSections(CUser $user) {
        $filter = [
            'IBLOCK_ID' => IblockTools::find(Iblock::PARTNER_TYPE, Iblock::FEED)->id(),
            'ACTIVE' => 'Y'
        ];
        if (self::isGroupFilterEnabled($user)) {
            $filter[] = [
                'LOGIC' => 'OR',
                ['UF_USER_GROUP' => false],
                ['UF_USER_GROUP' => CUser::GetUserGroup($user->GetID())]
            ];
        }
        return iter\toArray(Iblock::iter(CIBlockSection::GetList([], $filter)));
    }

    static function stockFiles(CUser $user) {
        $b = CGroup::GetIDByCode(UserGroup::BABYLISS);
        $files = [
            // don't change the paths. uploaded by a third-party.
            'Braun'     => [12, '/partneram/ostatki_V/ostatki_braun1.xls'],
            "De'Longhi" => [10, '/partneram/ostatki_V/ostatki_braun2.xls'],
            'Babyliss'  => [$b, '/partneram/ostatki_V/ostatki_babyliss.xls']
        ];
        if (self::isGroupFilterEnabled($user)) {
            $userGroups = CUser::GetUserGroup($user->GetID());
            return array_filter($files, function ($pair) use ($userGroups) {
                list($group) = $pair;
                return in_array($group, $userGroups);
            });
        } else {
            return $files;
        }
    }
}