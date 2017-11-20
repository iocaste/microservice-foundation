<?php

namespace Iocaste\Microservice\Foundation\Composer;

use Composer\Script\Event;

/**
 * Class MicroserviceScripts
 */
class MicroserviceScripts
{
    /**
     * Runs microservice post-install / post-update commands
     *
     * @return void
     */
    public static function postUpdate(Event $event)
    {
        //require_once $event->getComposer()->getConfig()->get('vendor-dir').'/autoload.php';

        // @todo implement.
    }
}
