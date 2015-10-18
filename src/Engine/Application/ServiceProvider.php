<?php namespace Engine\Application;

use Phalcon\Mvc\Url;
use Phalcon\Session\Adapter\Files as Session;
use Engine\DI\ServiceProvider as ServiceProviderContract;
use Engine\Dispatcher\Factory as Dispatcher;
use Engine\Http\Request\Factory as Request;
use Engine\Http\Response\Factory as Response;

class ServiceProvider implements ServiceProviderContract
{

    public function boot()
    {
        // TODO: Implement boot() method.
        $this->registerUrl()
             ->registerSession()
             ->registerDispatcher()
             ->registerRequest()
             ->registerResponse();
    }

    public function ready()
    {
        // TODO: Implement ready() method.
    }

    /**
     * Setup a base URI so that all generated URIs
     *
     * @return static
     */
    protected function registerUrl()
    {
        di()->setShared('url', function () {
            $url = new Url();
            $url->setBaseUri(env('base_url', ''));

            return $url;
        });

        return $this;
    }

    /**
     * Start the session the first time a component requests the session service
     *
     * @return static
     */
    protected function registerSession()
    {
        di()->setShared('session', function () {
            $session = new Session();
            $session->start();

            return $session;
        });

        return $this;
    }

    /**
     * Register application dispatcher
     *
     * @return static
     */
    protected function registerDispatcher()
    {
        di()->setShared('dispatcher', function() {
            return new Dispatcher();
        });

        return $this;
    }

    /**
     * Register application request
     *
     * @return static
     */
    protected function registerRequest()
    {
        di()->setShared('request', function () {
            return new Request();
        });

        return $this;
    }

    /**
     * Register application response
     *
     * @return static
     */
    protected function registerResponse()
    {
        di()->setShared('response', function() {
            return new Response();
        });

        return $this;
    }
}