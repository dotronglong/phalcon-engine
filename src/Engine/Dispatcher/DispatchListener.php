<?php namespace Engine\Dispatcher;

use Phalcon\Mvc\Dispatcher;

class DispatchListener
{
    public function beforeDispatchLoop($event, Dispatcher $dispatcher)
    {
        $resolver = di('resolver');
        $dispatcher->setControllerName($resolver->run('dispatch:controller', function() {
            $router     = di('router');
            $moduleName = $router->getModuleName();
            $ctlName    = ucfirst($router->getControllerName());
            return "\\App\\Modules\\$moduleName\\Controllers\\$ctlName";
        }));
    }
}