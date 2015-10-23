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

        // Run routing
        $router = di('router');
        $router->handle($uri);

        // Pass the processed router parameters to the dispatcher
        $dispatcher = di('dispatcher');

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