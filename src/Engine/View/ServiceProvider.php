<?php namespace Engine\View;

use Engine\DI\ServiceProvider as ServiceProviderContract;
use Engine\View\Factory as View;

class ServiceProvider implements ServiceProviderContract
{

    public function boot()
    {
        // TODO: Implement boot() method.
        di()->setShared('view', function() {
            return new View();
        });
    }

    public function ready()
    {
        // TODO: Implement ready() method.
    }
}