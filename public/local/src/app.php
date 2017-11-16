<?php

namespace App;

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Core\Env;
use Core\NewsListLike;
use Bitrix\Main\Config\Configuration;
use Core\Underscore as _;
use Core\Util;

if (class_exists('Bitrix\Main\Loader')) {
    Loader::includeModule('iblock');
}

class App extends \Core\App {
    const SITE_ID = 's1';
    const NEWSLETTER_ID = 1;

    function init() {
        EventHandlers::attach();
    }

    function assert($cond, $message = '') {
        if ($cond) {
            return;
        }
        if (self::env() === Env::DEV) {
            throw new \Exception($message);
        } else {
            // TODO log to sentry
            return;
        }
    }

    function layoutContext() {
        // TODO memoize
        // TODO refactor deps: see usages
        $sentryConfig = _::get(Configuration::getValue('app'), 'sentry');
        $server = Application::getInstance()->getContext()->getServer();
        return [
            'isAjax' => $server->get('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest', // header set by jquery
            'auth' => Auth::links(),
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
            'js/vendor/lodash.js',
            'js/vendor/jquery.validate.js',
            'modules.js',
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

    static function render($path, $data = [], $opts = []) {
        return parent::render(Util::joinPath([$_SERVER['DOCUMENT_ROOT'], SITE_TEMPLATE_PATH, $path]), $data, $opts);
    }

    static function fileSize($path) {
        return Util::humanFileSize(filesize(Util::joinPath([$_SERVER['DOCUMENT_ROOT'], $path])), [
            'units' => explode(',', 'b,kb,mb,gb,tb,pb,eb,zb,yb')
        ]);
    }

    static function docLinkAttrs($ext) {
        $forceDownload = ['7z']; // some servers send the wrong content-type for these extensions
        return array_merge(['target' => '_blank'], in_array($ext, $forceDownload) ? ['download' => ''] : []);
    }
}

class Events {
}

class Iblock extends \Core\Iblock {
    const CHECKBOX_TRUE_VALUE = 'да';

    const CONTENT_TYPE = 'content';
    const BRANDS = 'brands';
    const CLIENTS = 'clients';
    const CERTIFICATES = 'certificates';
    const ARTICLES = 'articles';
    const VIDEOS = 'videos';
    const RESPONSIVE_BANNERS = 'responsive_banners';

    const CATALOG_TYPE = 'catalog';

    const PARTNER_TYPE = 'partner';
    const DOCUMENTS = 'documents';
    const FEED = 'feed';
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

class Catalog {
    static $perPageOpts = [
        '12' => '12',
        '24' => '24',
        '48' => '48',
        'all' => '1000',
    ];
    static $perPageDefault = '12';

    static function sectionState() {
        $remember = ['sort'];
        $defaults = ['sort' => 'show_counter:desc'];
        $keys = array_keys($defaults);
        $params = _::pick(array_merge($defaults, $_SESSION, $_REQUEST), $keys);
        list($_field, $order) = explode(':', $params['sort']);
        $field = $_field === 'price' ? Product::BASE_PRICE : $_field;
        foreach ($remember as $k) {
            // mutate
            $_SESSION[$k] = $params[$k];
        }
        return [
            'sort' => ['field' => $field, 'order' => $order],
            'params' => $params
        ];
    }
}