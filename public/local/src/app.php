<?php

namespace App;

use Bex\Tools\Iblock\IblockTools;
use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Configuration;
use CEvent;
use CIBlockElement;
use Core\Env;
use Core\NewsListLike;
use Core\Underscore as _;
use Core\Util;
use Raven_Client;

if (class_exists('Bitrix\Main\Loader')) {
    Loader::includeModule('iblock');
}

class App extends \Core\App {
    const SITE_ID = 's1';
    const NEWSLETTER_ID = 1;

    /** @var Raven_Client */
    private $raven = null;

    function init() {
        EventHandlers::attach();
    }

    function layoutContext() {
        global $APPLICATION;
        // TODO memoize
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
            ],
            'showBodyClass' => function () use (&$APPLICATION) {
                $APPLICATION->AddBufferContent(function () use (&$APPLICATION) {
                    return $APPLICATION->GetProperty('body_class', '');
                });
            }
        ];
    }

    function withRaven(callable $f) {
        if ($this->raven === null) {
            $dsn = _::get(Configuration::getValue('app'), 'sentry.dsn');
            $this->raven = new Raven_Client($dsn, [
                'environment' => self::env()
            ]);
        }
        if (function_exists('curl_init')) {
            return $f($this->raven);
        }
        return null;
    }

    function assert($cond, $message = '') {
        if ($cond) {
            return;
        }
        if (self::env() === Env::DEV) {
            throw new \Exception($message);
        } else {
            self::withRaven(function (Raven_Client $raven) use ($message) {
                return $raven->captureMessage($message, [], [], true);
            });
        }
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
            'js/vendor/additional-methods.js', // jquery.validate extension
            'js/vendor/jquery.maskedinput.js',
            'js/vendor/jquery.matchHeight.js',
            'modules.js',
            'js/app.js',
            'js/legacy.js',
        ]);
        return [
            'styles' => $styles,
            'scripts' => $scripts
        ];
    }

    static function submitContactForm($params) {
        // TODO error handling
        $fields = _::pick($params, ['NAME', 'PHONE', 'EMAIL', 'MESSAGE'], true);
        $el = new CIBlockElement();
        $isAdded = $el->Add([
            'IBLOCK_ID' => IblockTools::find(Iblock::INBOX_TYPE, Iblock::CONTACT_FORM)->id(),
            'NAME' => $fields['NAME'].' - '.date('d.m.Y'),
            'PROPERTY_VALUES' => $fields
        ]);
        self::getInstance()->assert($isAdded);
        $emailTo = Option::get('main', 'email_from');
        self::getInstance()->assert($emailTo);
        $isSent = CEvent::Send(Events::CONTACT_FORM, App::SITE_ID, array_merge($fields, [
            'EMAIL_TO' => $emailTo
        ]));
        if (self::env() !== Env::DEV) {
            self::getInstance()->assert($isSent);
        }
    }
}

class View extends \Core\View {
    use NewsListLike;

    static function render($path, $data = [], $opts = []) {
        return parent::render(Util::joinPath([$_SERVER['DOCUMENT_ROOT'], SITE_TEMPLATE_PATH, $path]), $data, $opts);
    }

    static function genericErrorMessageHtml() {
        return self::render('partials/generic_error.php', [
            'reported' => _::get(Configuration::getValue('app'), 'sentry.enabled', false),
            'email' => Option::get('main', 'email_from', null)
        ]);
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
    const CONTACT_FORM = 'CONTACT_FORM';
}

class Iblock extends \Core\Iblock {
    const CHECKBOX_TRUE_VALUE = 'да';

    /** Региональные сервис-центры BRAUN */
    const REGION_TYPE = 'region';
    /** технические заключения */
    const REPORTS_ID = 10;

    const CONTENT_TYPE = 'content';
    const CLIENTS = 'clients';
    const CERTIFICATES = 'certificates';
    const ARTICLES = 'articles';
    const VIDEOS = 'videos';
    const RESPONSIVE_BANNERS = 'responsive_banners';
    const OUR_LOCATIONS = 'our_locations';

    const CATALOG_TYPE = 'catalog';
    /** products */
    const FURNITURE = 'furniture';

    const PARTNER_TYPE = 'partner';
    const DOCUMENTS = 'documents';
    const FEED = 'feed';

    const NEWS_TYPE = 'news';
    const NEWS = 'news';

    const INBOX_TYPE = 'inbox';
    const CONTACT_FORM = 'contact_form';
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