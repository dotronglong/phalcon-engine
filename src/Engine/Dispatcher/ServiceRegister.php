<?php namespace Engine\Dispatcher;

use Engine\DI\ServiceRegister as ServiceRegisterContract;
use Engine\Dispatcher\Factory as Dispatcher;
use Engine\DI\HasInjection;

class ServiceRegister implements ServiceRegisterContract
{
    use HasInjection;

    public function onBoot()
    {
        // TODO: Implement onBoot() method.
        $this->getDI()->setShared('dispatcher', function() {
            $di = $this->getDI();
            $em = $di->getEventsManager();

            $dispatcher = new Dispatcher();
            $dispatcher->setDI($di);
            $dispatcher->setEventsManager($em);

            return $dispatcher;
        });
    }

    public function onReady()
    {
        // TODO: Implement onReady() method.
    }
}