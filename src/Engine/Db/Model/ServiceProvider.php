<?php namespace Engine\Db\Model;

use Engine\DI\Contract as DI;
use Engine\DI\ServiceProvider as ServiceProviderContract;
use Phalcon\Mvc\Model\Manager as ModelsManager;

class ServiceProvider implements ServiceProviderContract
{

    public function boot(DI $di)
    {
        // TODO: Implement boot() method.
        $di->setShared('modelsManager', function() {
            return new ModelsManager();
        });
    }

    public function ready()
    {
        // TODO: Implement ready() method.
    }
}