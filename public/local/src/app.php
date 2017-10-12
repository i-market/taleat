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
        $sentryConfig = _::get(Configuration::getValue('app'), 'sentry');
        return [
            'auth' => [
                'registerLink' => '/login/?register=yes&backurl=%2Flogin%2F',
                'profileLink' => '/personal/?backurl=%2F',
                'loginLink' => '#',
                'logoutLink' => '/?logout=yes'
            ],
            'sentry' => [
                'enabled' => $sentryConfig['enabled'],
                'env' => self::env(),
                'publicDsn' => $sentryConfig['public_dsn']
            ],
            'copyrightYear' => date('Y')
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

