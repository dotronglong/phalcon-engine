<?php namespace Engine\Application;

use Engine\Exception\NullPointerException;
use Phalcon\Mvc\Application;
use Phalcon\Http\ResponseInterface;
use Phalcon\Mvc\View as PhalconView;

class Factory extends Application
{
    /**
     * Handles a MVC request
     *
     * @param string uri
     * @return \Phalcon\Http\ResponseInterface|boolean
     * @throws NullPointerException
     */
    public function handle($uri = null)
    {
        $di = di();
        if (is_null($di)) {
            throw new NullPointerException('Dependency Injector must be defined');
        }

        $em = $this->getEventsManager();
        if (is_null($em)) {
            throw new NullPointerException('EventsManager must be defined');
        }

        // Fire application:boot event
        if ($em->fire('application:boot', $this) === false) {
            return false;
        }

        // Handle Router
        $router = $di->getShared('router');
        $router->handle($uri);

        // Handle Request
        $request = $di->getShared('request');
        $request->setRouter($router);

        // Pass the processed router parameters to the dispatcher
        $dispatcher = $di->getShared('dispatcher');
        $dispatcher->setModuleName($router->getModuleName());
		$dispatcher->setNamespaceName($router->getNamespaceName());
		$dispatcher->setControllerName($router->getControllerName());
		$dispatcher->setActionName($router->getActionName());
		$dispatcher->setParams($router->getParams());

        // Fire application:beforeHandleRequest event
        if ($em->fire('application:beforeHandleRequest', $this, $dispatcher) === false) {
            return false;
        }

        // Dispatch the request
        if ($dispatcher->dispatch()) {
            // There should be a response
            $response = null;
            $returnedValue = $dispatcher->getReturnedValue();
            if ($returnedValue instanceof PhalconView) {
                $response = $di->get(ResponseInterface::class);
                $response->setContent($returnedValue->getContent());
            } elseif ($returnedValue instanceof ResponseInterface) {
                $response = $returnedValue;
            }

            if ($response instanceof ResponseInterface) {
                $response->send();
            }
        }

        // Fire application:afterDispatch event
        if ($em->fire('application:afterHandleRequest', $this, $dispatcher) === false) {
            return false;
        }
    }
}