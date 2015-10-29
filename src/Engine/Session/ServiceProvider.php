<?php namespace Engine\Session;

use Engine\DI\ServiceProvider as ServiceProviderContract;
use Phalcon\Session\Adapter\Files as Session;
use Engine\DI\HasInjection;

class ServiceProvider implements ServiceProviderContract
{
    use HasInjection;

    public function boot()
    {
        // TODO: Implement boot() method.
        $this->getDI()->setShared('session', function () {
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