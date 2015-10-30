<?php namespace Engine\Resolver;

use Engine\DI\ServiceRegister as ServiceRegisterContract;
use Engine\DI\HasInjection;
use Engine\Resolver\Factory as Resolver;

class ServiceRegister implements ServiceRegisterContract
{
    use HasInjection;

    public function onBoot()
    {
        // TODO: Implement onBoot() method.
        $this->getDI()->setShared('resolver', function() {
            return new Resolver();
        });
    }

    public function onReady()
    {
        // TODO: Implement onReady() method.
    }


}