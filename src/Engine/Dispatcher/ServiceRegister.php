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
        $di = $this->getDI();
        $this->getDI()->setShared('dispatcher', function() use ($di) {
            $dispatcher = new Dispatcher();
            $dispatcher->setDI($di);

            return $dispatcher;
        });
    }

    public function onReady()
    {
        // TODO: Implement onReady() method.
    }
}