<?php namespace Engine\Debug;

use Engine\DI\ServiceRegister as ServiceRegisterContract;
use Engine\DI\HasInjection;

class ServiceRegister implements ServiceRegisterContract
{
    use HasInjection;

    public function onBoot()
    {
        // TODO: Implement onBoot() method.
        $allowDebug = env('APP_DEBUG', false);
        if ($allowDebug) {
            new \Whoops\Provider\Phalcon\WhoopsServiceProvider($this->getDI());
        }
    }

    public function onReady()
    {
        // TODO: Implement onReady() method.
    }
}