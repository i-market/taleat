<?php

namespace App;

use Bitrix\Main\Loader;
use Core\NewsListLike;
use Bitrix\Main\Config\Configuration;
use Core\Underscore as _;
use Core\Util;

if (class_exists('Bitrix\Main\Loader')) {
    Loader::includeModule('iblock');
}

class App extends \Core\App {
    const SITE_ID = 's1';

    function init() {
    }

    function layoutContext() {
        // TODO memoize
        $sentryConfig = _::get(Configuration::getValue('app'), 'sentry');
        return [
            'auth' => [
                'registerLink' => '/login/?register=yes&backurl=%2Flogin%2F',
                'profileLink' => '/personal/?backurl=%2F',
                // TODO login link
                'loginLink' => '#',
                'logoutLink' => '/?logout=yes'
            ],
            'catalog' => [
                'checkoutLink' => '/personal/order/make/'
            ],
            'sentry' => [
                'enabled' => $sentryConfig['enabled'],
                'env' => self::env(),
                'publicDsn' => $sentryConfig['public_dsn']
            ]
        ];
    }

    static function assets() {
        $styles = array_merge(
            [
                'https://fonts.googleapis.com/css?family=Roboto:300,400,700'
            ],
            array_map([View::class, 'asset'], [
                'css/main.css',
            ])
        );
        $scripts = array_map([View::class, 'asset'], [
            'js/main.js',
            'js/app.js',
            'js/legacy.js',
        ]);
        return [
            'styles' => $styles,
            'scripts' => $scripts
        ];
    }
}

class View extends \Core\View {
    use NewsListLike;

    static function render($path, $data = []) {
        return parent::render(Util::joinPath([$_SERVER['DOCUMENT_ROOT'], SITE_TEMPLATE_PATH, $path]), $data);
    }
}

class Events {
}

class Iblock {
    const CHECKBOX_TRUE_VALUE = 'да';

    const CONTENT_TYPE = 'content';
    const BRANDS = 'brands';
    const CLIENTS = 'clients';
    const CERTIFICATES = 'certificates';
    const ARTICLES = 'articles';
    const VIDEOS = 'videos';
}

class Videos {
    static function youtubeId($url) {
        $matchesRef = [];
        if (preg_match('~(?:v=|youtu\.be/|youtube\.com/embed/)([a-z0-9_-]+)~i', $url, $matchesRef)) {
            return $matchesRef[1];
        } else {
            return null;
        }
    }

    static function embedUrl($id) {
        return "https://www.youtube.com/embed/{$id}?rel=0";
    }
}
