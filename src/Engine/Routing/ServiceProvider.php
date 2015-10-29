<?php namespace Engine\Routing;

use Engine\DI\ServiceProvider as ServiceProviderContract;
use Engine\Routing\Router\Factory as Router;
use Engine\DI\HasInjection;

class ServiceProvider implements ServiceProviderContract
{
    use HasInjection;

    public function boot()
    {
        // TODO: Implement boot() method.
        $this->getDI()->setShared('router', function() {
            return new Router(false);
        });
    }

    public function ready()
    {
        // TODO: Implement ready() method.
        require PATH_FILE_ROUTES;
    }
}