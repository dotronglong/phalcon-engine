<?php namespace Engine\Routing;

use Engine\DI\ServiceProvider as ServiceProviderContract;
use Engine\Routing\Router;

class ServiceProvider implements ServiceProviderContract
{
    public function boot()
    {
        // TODO: Implement boot() method.
        di()->setShared('router', function() {
            return new Router(false);
        });
    }

    public function ready()
    {
        // TODO: Implement ready() method.
    }
}