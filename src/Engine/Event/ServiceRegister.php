<?php namespace Engine\Event;

use Engine\DI\ServiceRegister as ServiceRegisterContract;
use Engine\DI\HasInjection;
use Phalcon\Events\Manager as EventsManager;

class ServiceRegister implements ServiceRegisterContract
{
    use HasInjection;

    public function onBoot()
    {
        // TODO: Implement onBoot() method.
        $this->getDI()->setShared('eventsManager', function() {
            return new EventsManager();
        });
    }

    public function onReady()
    {
        // TODO: Implement onReady() method.
    }


}