<?php namespace Engine\Session;

use Engine\DI\ServiceRegister as ServiceRegisterContract;
use Phalcon\Session\Adapter\Files as Session;
use Engine\DI\HasInjection;

class ServiceRegister implements ServiceRegisterContract
{
    use HasInjection;

    public function onBoot()
    {
        // TODO: Implement onBoot() method.
        $this->getDI()->setShared('session', function () {
            $session = new Session();
            if (!$session->isStarted()) {
                $session->start();
            }

            return $session;
        });
    }

    public function onReady()
    {
        // TODO: Implement onReady() method.
    }
}