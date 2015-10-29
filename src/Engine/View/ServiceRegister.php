<?php namespace Engine\View;

use Engine\DI\ServiceRegister as ServiceRegisterContract;
use Engine\View\Factory as View;
use Engine\DI\HasInjection;

class ServiceRegister implements ServiceRegisterContract
{
    use HasInjection;

    public function onBoot()
    {
        // TODO: Implement onBoot() method.
        $this->getDI()->setShared('view', function() {
            return new View();
        });
    }

    public function onReady()
    {
        // TODO: Implement onReady() method.
    }
}