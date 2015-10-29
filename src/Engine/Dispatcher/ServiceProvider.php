<?php namespace Engine\Dispatcher;

use Engine\DI\ServiceProvider as ServiceProviderContract;
use Engine\Dispatcher\Factory as Dispatcher;
use Phalcon\Events\Manager as EventsManager;
use Engine\DI\HasInjection;

class ServiceProvider implements ServiceProviderContract
{
    use HasInjection;

    public function boot()
    {
        // TODO: Implement boot() method.
        $this->getDI()->setShared('dispatcher', function() use ($this) {
            $dispatcher = new Dispatcher();
            $dispatcher->setDI($this->getDI());
            $eventsManager = $this->getDI()->getShared('eventsManager');
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