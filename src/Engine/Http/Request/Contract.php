<?php namespace Engine\Http\Request;

use Phalcon\Http\RequestInterface;
use Engine\Routing\Router\Contract as Router;

interface Contract extends RequestInterface
{
    /**
     * Set Router
     *
     * @param Router $router
     * @return void
     */
    public function setRouter(Router $router);

    /**
     * Get Router
     *
     * @return Router
     */
    public function getRouter();
}