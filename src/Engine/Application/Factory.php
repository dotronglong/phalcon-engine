<?php namespace Engine\Application;

use Phalcon\Mvc\Application;

class Factory extends Application
{
    /**
     * Handles a MVC request
     *
     * @param string uri
     * @return \Phalcon\Http\ResponseInterface|boolean
     */
    public function handle($uri = null)
    {
        $eventsManager = $this->getEventsManager();

        // Fire application:boot event
        if ($eventsManager->fire('application:boot', $this) === false) {
            return false;
        }

        // Handle request by router
        $router = di('router');
        $router->handle($uri);

        // Setup Request
        $request = di('request');
        $request->setRouter($router);

        // Pass the processed router parameters to the dispatcher
        $dispatcher = di('dispatcher');
        $dispatcher->setRequest($request);

        // Fire application:beforeDispatch event
        if ($eventsManager->fire('application:beforeDispatch', $this, $dispatcher) === false) {
            return false;
        }

        // Dispatch the request
        $dispatcher->dispatch();

        // Fire application:afterDispatch event
        if ($eventsManager->fire('application:afterDispatch', $this, $dispatcher) === false) {
            return false;
        }
    }
}