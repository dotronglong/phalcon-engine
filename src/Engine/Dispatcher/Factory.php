<?php namespace Engine\Dispatcher;

use Phalcon\Mvc\Dispatcher;

class Factory extends Dispatcher
{
    public function dispatch()
    {
        // Dispatch loop
        $finished = false;
        while (!$finished) {

            $finished = true;

            $controllerClass = $this->getControllerName() . "Controller";

            // Instantiating the controller class via DI Factory
            $controller = di($controllerClass);

            // Execute the action
            call_user_func_array(array($controller, $actionName . "Action"), $params);

            // '$finished' should be reloaded to check if the flow was forwarded to another controller
            $finished = true;
        }
    }
}