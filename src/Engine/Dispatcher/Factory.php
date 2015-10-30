<?php namespace Engine\Dispatcher;

use Phalcon\Mvc\Dispatcher;
use Engine\Http\HasRequest;

class Factory extends Dispatcher implements Contract
{
    use HasRequest;

    /**
     * Preparing for dispatching
     *
     * @return void
     */
    protected function preDispatch()
    {
        $router     = $this->request->getRouter();
        $moduleName = $router->getModuleName();
        $ctlName    = $router->getControllerName();
        $resolver   = $this->getDI()->get('resolver');

        $this->setControllerName($resolver->run('dispatch:controller', function() use ($moduleName, $ctlName) {
            return "\\App\\Modules\\$moduleName\\Controllers\\$ctlName";
        }, [$router]));
        $this->setActionName($router->getActionName());
        $this->setParams($router->getParams());
    }

    public function dispatch()
    {
        // Run pre-dispatch
        $this->preDispatch();

        // Dispatch loop
        $finished = false;
        while (!$finished) {

            $finished = true;

            $controllerClass = $this->getControllerName() . "Controller";
            $actionName      = $this->getActionName();
            $params          = $this->getParams();

            // Instantiating the controller class via DI Factory
            $controller      = $this->getDI()->get($controllerClass);

            // Execute the action
            call_user_func_array(array($controller, $actionName . "Action"), $params);

            // '$finished' should be reloaded to check if the flow was forwarded to another controller
            $finished = true;
        }
    }
}