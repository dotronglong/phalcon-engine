<?php namespace Engine\Application;

use Phalcon\Mvc\Application;

class Factory extends Application
{
    public function handle()
    {
        // Run routing
        $router = di('router');
        $router->handle();
        $moduleName = $router->getModuleName();
        $ctlName    = $router->getControllerName();

        // Pass the processed router parameters to the dispatcher
        $dispatcher = di('dispatcher');
        $dispatcher->setControllerName("\App\Modules\\$moduleName\Controllers\\$ctlName");
        $dispatcher->setActionName($router->getActionName());
        $dispatcher->setParams($router->getParams());

        // Dispatch the request
        $dispatcher->dispatch();
    }
}