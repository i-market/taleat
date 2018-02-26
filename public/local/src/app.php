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
use CSite;
use CUser;
use Raven_Client;

if (class_exists('Bitrix\Main\Loader')) {
    Loader::includeModule('iblock');
}

class App extends \Core\App {
    const SITE_ID = 's1';

    const NEWSLETTER_ID = 1;
    const NEWSLETTER_FORMAT = 'html';
    const PARTNER_NEWSLETTER_ID = 2;
    const PARTNER_NEWSLETTER_FORMAT = 'html';

    /** @var Raven_Client */
    private $raven = null;

    function init() {
        EventHandlers::attach();
    }

    // TODO better logging
    function log(...$entries) {
        $path = Util::joinPath([$_SERVER['DOCUMENT_ROOT'], 'local/app.log']);
        $sep = ",\n";
        $str = join($sep, array_map(_::partialRight('var_export', true), $entries)).$sep;
        return $this->assert(file_put_contents($path, $str, FILE_APPEND | LOCK_EX), 'logging issue');
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

    static function chooseSiteTemplate() {
        if (self::env() === Env::DEV && isset($_REQUEST['legacy'])) {
            return 'main_page';
        }
        $main = [
            '/index.php',
            '/content-examples',
            '/catalog',
            '/ajax',
            '/login',

            '/partneram', // TODO implement redirects for deprecated pages

            '/news',
            '/articles',
            '/videos',
            '/region',
            '/otzivi',
            '/contacts',
            '/priemnie-punkti',
            '/search',
            '/buy',
            '/terms',
            '/auth',
            '/admin',
            '/law',
            '/terms',

            '/personal/index.php',
            '/personal/cart',
            '/personal/order/index.php',
            '/personal/order/make',
            '/personal/order/bill',
            '/personal/order/detail',
            '/personal/order/cancel',
        ];
        foreach ($main as $prefix) {
            if (CSite::InDir($prefix)) {
                return 'main';
            }
        }
        return 'main_page';
    }

    function withRaven(callable $f) {
        global $USER;
        if (self::env() === Env::DEV || !function_exists('curl_init')) {
            return null;
        }
        if ($this->raven === null) {
            $dsn = _::get(Configuration::getValue('app'), 'sentry.dsn');
            $this->raven = new Raven_Client($dsn, [
                'environment' => self::env()
            ]);
        }
        if (is_object($USER) && $USER->IsAuthorized()) {
            $this->raven->user_context([
                'id' => $USER->GetID(),
                'username' => $USER->GetLogin(),
                'email' => $USER->GetEmail()
            ]);
        }
        return $f($this->raven);
    }

    function assert($cond, $message = '') {
        if ($cond) {
            return;
        }
        if (self::env() === Env::DEV) {
            throw new \Exception($message);
        } else {
            self::withRaven(function (Raven_Client $raven) use ($message) {
                return $raven->captureException(new \Exception($message));
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
            'EMAIL_TO' => $emailTo // TODO unused
        ]));
        if (self::env() !== Env::DEV) {
            self::getInstance()->assert($isSent);
        }
    }
    
    static function holidayMode() {
        $res = CUser::GetList($o, $b, array("ID_EQUAL_EXACT" => 1), array("SELECT"=>array("UF_HOLYDAY", "UF_HOLYDAY_TO")));
        if ($ob = $res->Fetch()){
            if ($ob["UF_HOLYDAY"] && strtotime($ob["UF_HOLYDAY_TO"]) > time()) {
                return [
                    'isEnabled' => true,
                    'to' => $ob["UF_HOLYDAY_TO"]
                ];
            }
        }
        return [
            'isEnabled' => false,
            'to' => null
        ];
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

    static function privacyPolicyError() {
        return 'Пожалуйста, дайте согласие на обработку персональных данных.';
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
    const NEW_UNCONFIRMED_PARTNER = 'NEW_UNCONFIRMED_PARTNER';
    const TEH_ZAKL_UPDATE = 'TEH_ZAKL_UPDATE';
    const UNPAID_ORDER_REMINDER = 'UNPAID_ORDER_REMINDER';
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

class UserGroup {
    const UNCONFIRMED_PARTNER = 'unconfirmed_partner';
    const FULL_BRAND_ACCESS = 'full_brand_access';
    const BABYLISS = 'babyliss';
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

class Order {
    static function isPayable($order) {
        return $order['STATUS_ID'] === OrderStatus::ACCEPTED
            && $order['PAYED'] !== 'Y'
            && $order['CANCELED'] !== 'Y';
    }
}

class OrderStatus {
    const COMPLETED = 'F';
    const OUT_OF_STOCK = 'O';
    /** accepted, waiting for payment */
    const ACCEPTED = 'A';
    /*
    array (
      'N' =>
      array (
        'ID' => 'N',
        'NAME' => 'Заказ в обработке',
      ),
      'O' =>
      array (
        'ID' => 'O',
        'NAME' => 'Нет на складе',
      ),
      'A' =>
      array (
        'ID' => 'A',
        'NAME' => 'Принят, ожидается оплата',
      ),
      'S' =>
      array (
        'ID' => 'S',
        'NAME' => 'Товар поступил на склад',
      ),
      'P' =>
      array (
        'ID' => 'P',
        'NAME' => 'Оплачен',
      ),
      'F' =>
      array (
        'ID' => 'F',
        'NAME' => 'Отправлен',
      ),
    )
    */
}