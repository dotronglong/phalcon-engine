<?php namespace Engine\Tests\DI;

use Phalcon\DiInterface;
use Phalcon\Di\ServiceInterface;
use Engine\DI\Container as DI;

class ContainerTest extends \PHPUnit_Framework_TestCase
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
    public function testCanSetService($di)
    {
        $service = $di->set($this->abstractClass, $this->definitionClass);
        $this->assertInstanceOf(ServiceInterface::class, $service);
        return $di;
    }

    /**
     * @depends testCanSetService
     */
    public function testCanResolveAbstract($di)
    {
        $instance = $di->get($this->abstractClass);
        $this->assertInstanceOf($this->definitionClass, $instance);
    }

    /**
     * @depends testImplementedDiInterface
     */
    public function testCanSetSharedService($di)
    {
        $service = $di->setShared($this->abstractClass, $this->definitionClass);
        $this->assertInstanceOf(ServiceInterface::class, $service);
        return $di;
    }

    /**
     * @depends testCanSetSharedService
     */
    public function testCanShareService($di)
    {
        $this->assertInstanceOf($this->definitionClass, $di->getShared($this->abstractClass));
    }
}