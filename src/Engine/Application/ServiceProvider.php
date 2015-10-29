<?php namespace Engine\Application;

use Engine\DI\ServiceProvider as ServiceProviderContract;
use Engine\DI\HasInjection;

class ServiceProvider implements ServiceProviderContract
{
    use HasInjection;
    
    public function boot()
    {
        // TODO: Implement boot() method.
    }

    public function ready()
    {
        // TODO: Implement ready() method.
    }
}