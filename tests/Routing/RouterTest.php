<?php namespace Engine\Tests\Ruouting;

use Engine\Tests\TestCase;
use Engine\Routing\Router\Factory as Router;
use Engine\Routing\Router\Contract as RouterContract;

class RouterTest extends TestCase
{
    public function testImplementContract()
    {
        $router = new Router();
        $this->assertInstanceOf(RouterContract::class, $router);
        return $router;
    }

    /**
     * @depends testImplementContract
     */
    public function testSetControllerName(Router $router)
    {
        $router = clone $router;
        $router->setControllerName('my_controller');
        $this->assertEquals('my_controller', $router->getControllerName());
    }

    /**
     * @depends testImplementContract
     */
    public function testSetModuleName(Router $router)
    {
        $router = clone $router;
        $router->setModuleName('my_module');
        $this->assertEquals('my_module', $router->getModuleName());
    }

    /**
     * @depends testImplementContract
     */
    public function testSetActionName(Router $router)
    {
        $router = clone $router;
        $router->setActionName('my_action');
        $this->assertEquals('my_action', $router->getActionName());
    }

    /**
     * @depends testImplementContract
     */
    public function testSetNamespaceName(Router $router)
    {
        $router = clone $router;
        $router->setNamespaceName('my_namespace');
        $this->assertEquals('my_namespace', $router->getNamespaceName());
    }
}