<?php namespace Engine\View;

use Engine\DI\ServiceRegister as ServiceRegisterContract;
use Engine\DI\HasInjection;
use Engine\View\Contract as ViewContract;
use Engine\View\Factory as View;

class ServiceRegister implements ServiceRegisterContract
{
    use HasInjection;

    public function onBoot()
    {
        // TODO: Implement onBoot() method.
        $this->getDI()->set(ViewContract::class, View::class);
        $this->getDI()->setShared('view', function() {
            return di(ViewContract::class);
        });
    }

    public function onReady()
    {
        // TODO: Implement onReady() method.
        $this->getDI()->get('view')
             ->registerEngines([
                 '.phtml' => 'Phalcon\Mvc\View\Engine\Php',
                 '.volt'  => 'Phalcon\Mvc\View\Engine\Volt'
             ]);
    }
}