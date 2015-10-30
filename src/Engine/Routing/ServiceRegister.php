<?php namespace Engine\Routing;

use Engine\DI\ServiceRegister as ServiceRegisterContract;
use Engine\Routing\Router\Factory as Router;
use Engine\Routing\Router\Contract as RouterContract;
use Engine\DI\HasInjection;

class ServiceRegister implements ServiceRegisterContract
{
    use HasInjection;

    public function onBoot()
    {
        // TODO: Implement onBoot() method.
        $this->getDI()->setShared('router', function() {
            return new Router(false);
        });
        $this->getDI()->set(RouterContract::class, Router::class);
    }

    public function onReady()
    {
        // TODO: Implement onReady() method.
        require PATH_FILE_ROUTES;
    }
}