<?php namespace Engine\View;

use Engine\DI\ServiceProvider as ServiceProviderContract;
use Engine\View\Factory as View;
use Engine\DI\HasInjection;

class ServiceProvider implements ServiceProviderContract
{
    use HasInjection;

    public function boot()
    {
        // TODO: Implement boot() method.
        $this->getDI()->setShared('view', function() {
            return new View();
        });
    }

    public function ready()
    {
        // TODO: Implement ready() method.
    }
}