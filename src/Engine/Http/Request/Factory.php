<?php namespace Engine\Http\Request;

use Engine\Routing\Router\Contract as Router;
use Phalcon\Http\Request;

class Factory extends Request implements Contract
{
    /**
     * @var Router
     */
    protected $router;

    public function setRouter(Router $router)
    {
        // TODO: Implement setRouter() method.
        $this->router = $router;
    }

    public function getRouter()
    {
        // TODO: Implement getRouter() method.
        return $this->router;
    }
}