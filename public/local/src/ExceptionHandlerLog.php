<?php

namespace App;

use Bitrix\Main\Config\Configuration;
use Raven_Client;
use Core\Underscore as _;
use Bitrix\Main\Diag\ExceptionHandlerLog as h;

class ExceptionHandlerLog extends h {
    private $enabled = false;

    public function write($exception, $logType) {
        $ignore = [h::LOW_PRIORITY_ERROR, h::ASSERTION, h::IGNORED_ERROR];
        if ($this->enabled && !in_array($logType, $ignore)) {
            App::getInstance()->withRaven(function (Raven_Client $raven) use ($exception, $logType) {
                $raven->captureException($exception, [
                    'logType' => $logType
                ]);
            });
        }
    }

    public function initialize(array $options) {
        $appConfig = Configuration::getValue('app');
        $this->enabled = _::get($appConfig, 'sentry.enabled', false);
    }
}
