<?php namespace Engine\Http;

use Engine\DI\ServiceProvider as ServiceProviderContract;
use Engine\Http\Request\Factory as Request;
use Engine\Http\Response\Factory as Response;
use Engine\DI\HasInjection;

class ServiceProvider implements ServiceProviderContract
{
    use HasInjection;

    public function boot()
    {
        // TODO: Implement boot() method.
        $this->getDI()->setShared('request', function () {
            return new Request();
        });
        $this->getDI()->setShared('response', function() {
            return new Response();
        });
    }

    public function ready()
    {
        // TODO: Implement ready() method.
    }
}