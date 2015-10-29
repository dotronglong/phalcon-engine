<?php namespace Engine\Http;

use Engine\DI\ServiceRegister as ServiceRegisterContract;
use Engine\Http\Request\Factory as Request;
use Engine\Http\Response\Factory as Response;
use Engine\DI\HasInjection;

class ServiceRegister implements ServiceRegisterContract
{
    use HasInjection;

    public function onBoot()
    {
        // TODO: Implement onBoot() method.
        $this->getDI()->setShared('request', function () {
            return new Request();
        });
        $this->getDI()->setShared('response', function() {
            return new Response();
        });
    }

    public function onReady()
    {
        // TODO: Implement onReady() method.
    }
}