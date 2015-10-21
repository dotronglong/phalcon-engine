<?php namespace Engine\Tests\DI;

use Phalcon\DiInterface;
use Phalcon\Di\ServiceInterface;
use Engine\DI\Container as DI;
use Engine\Tests\TestCase;

class ContainerTest extends TestCase
{
    protected $abstractClass    = 'Engine\Contracts\Config';
    protected $definitionClass  = 'Engine\Config';

    public function testImplementedDiInterface()
    {
        $di = new DI();
        $this->assertInstanceOf(DiInterface::class, $di);
        return $di;
    }

    /**
     * @depends testImplementedDiInterface
     */
    public function testSetService($di)
    {
        $service = $di->set($this->abstractClass, $this->definitionClass);
        $this->assertInstanceOf(ServiceInterface::class, $service);
        return $di;
    }

    /**
     * @depends testSetService
     */
    public function testResolveAbstract($di)
    {
        $instance = $di->get($this->abstractClass);
        $this->assertInstanceOf($this->definitionClass, $instance);
    }

    /**
     * @depends testImplementedDiInterface
     */
    public function testSetSharedService($di)
    {
        $service = $di->setShared($this->abstractClass, $this->definitionClass);
        $this->assertInstanceOf(ServiceInterface::class, $service);
        return $di;
    }

    /**
     * @depends testSetSharedService
     */
    public function testShareService($di)
    {
        $this->assertInstanceOf($this->definitionClass, $di->getShared($this->abstractClass));
    }
}