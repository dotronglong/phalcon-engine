<?php namespace Engine\Dispatcher;

use Engine\DI\ServiceRegister as ServiceRegisterContract;
use Engine\DI\HasInjection;
use Phalcon\Mvc\Dispatcher;

class ServiceRegister implements ServiceRegisterContract
{
    use HasInjection;

    public function onBoot()
    {
        // TODO: Implement onBoot() method.
        $this->getDI()->setShared('dispatcher', function() {
            $di = $this->getDI();
            $em = $di->getEventsManager();
            $em->attach('dispatch', new DispatchListener());

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