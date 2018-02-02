<?php

namespace App;

use Bitrix\Main\Config\Configuration;
use Raven_Client;
use Core\Underscore as _;

class ExceptionHandlerLog extends \Bitrix\Main\Diag\ExceptionHandlerLog {
    private $enabled = false;

    public function write($exception, $logType) {
        if ($this->enabled) {
            App::getInstance()->withRaven(function (\Raven_Client $raven) use ($exception, $logType) {
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
