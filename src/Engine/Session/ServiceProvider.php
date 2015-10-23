<?php namespace Engine\Session;

use Engine\DI\Contract as DI;
use Engine\DI\ServiceProvider as ServiceProviderContract;
use Phalcon\Session\Adapter\Files as Session;

class ServiceProvider implements ServiceProviderContract
{

    public function boot(DI $di)
    {
        // TODO: Implement boot() method.
        $di->setShared('session', function () {
            $session = new Session();
            if (!$session->isStarted()) {
                $session->start();
            }

            return $session;
        });
    }

    public function ready()
    {
        // TODO: Implement ready() method.
    }
}