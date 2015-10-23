<?php namespace Engine\Http;

use Engine\DI\Contract as DI;
use Engine\DI\ServiceProvider as ServiceProviderContract;
use Engine\Http\Request\Factory as Request;
use Engine\Http\Response\Factory as Response;

class ServiceProvider implements ServiceProviderContract
{

    public function boot(DI $di)
    {
        // TODO: Implement boot() method.
        $di->setShared('request', function () {
            return new Request();
        });
        $di->setShared('response', function() {
            return new Response();
        });
    }

    public function ready()
    {
        // TODO: Implement ready() method.
    }
}