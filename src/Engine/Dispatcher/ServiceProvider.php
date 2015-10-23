<?php namespace Engine\Dispatcher;

use Engine\DI\Contract as DI;
use Engine\DI\ServiceProvider as ServiceProviderContract;
use Engine\Dispatcher\Factory as Dispatcher;
use Phalcon\Events\Manager as EventsManager;

class ServiceProvider implements ServiceProviderContract
{

    public function boot(DI $di)
    {
        // TODO: Implement boot() method.
        $di->setShared('dispatcher', function() use ($di) {
            $dispatcher = new Dispatcher();
            $dispatcher->setDI($di);
            $eventsManager = $di->getShared('eventsManager');
            if (is_null($eventsManager)) {
                $eventsManager = new EventsManager();
            }
            $dispatcher->setEventsManager($eventsManager);

            return $dispatcher;
        });
    }

    public function ready()
    {
        // TODO: Implement ready() method.
    }
}