<?php namespace Engine\Debug;

use Engine\DI\ServiceProvider as ServiceProviderContract;

class ServiceProvider implements ServiceProviderContract
{

    public function boot()
    {
        // TODO: Implement boot() method.
        $allowDebug = env('debug', false);
        if ($allowDebug) {
            new \Whoops\Provider\Phalcon\WhoopsServiceProvider(di());
        }
    }

    public function ready()
    {
        // TODO: Implement ready() method.
    }
}