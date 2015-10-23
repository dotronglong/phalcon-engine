<?php namespace Engine\Routing;

use Engine\DI\Contract as DI;
use Engine\DI\ServiceProvider as ServiceProviderContract;
use Engine\Routing\Router\Factory as Router;

class ServiceProvider implements ServiceProviderContract
{

    public function boot(DI $di)
    {
        // TODO: Implement boot() method.
        $di->setShared('router', function() {
            return new Router(false);
        });
    }

    public function ready()
    {
        // TODO: Implement ready() method.
        require PATH_FILE_ROUTES;
    }
}